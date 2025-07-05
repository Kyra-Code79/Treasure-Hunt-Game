<?php

namespace App\Controllers;

use App\Models\TreasureModel;
use App\Models\PlayerModel;
use App\Models\AttemptModel;
use App\Models\UserTreasureProgressModel;
use Exception;

class Game extends BaseController
{
    protected $treasureModel;
    protected $playerModel;
    protected $attemptModel;
    protected $progressModel;

    public function __construct()
    {
        $this->treasureModel = new TreasureModel();
        $this->playerModel = new PlayerModel();
        $this->attemptModel = new AttemptModel();
        $this->progressModel = new UserTreasureProgressModel();
    }

    public function index()
    {
        // Require authentication to play the game
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login or register to start playing the treasure hunt game!');
        }

        try {
            $userId = session()->get('user_id');
            
            // Check if user exists and has current_treasure_index
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            
            if (!$user) {
                return redirect()->to('/auth/login')->with('error', 'User not found. Please login again.');
            }
            
            // Initialize current_treasure_index if not set
            if (!isset($user['current_treasure_index']) || $user['current_treasure_index'] === null) {
                $userModel->update($userId, ['current_treasure_index' => 1]);
            }
            
            $currentTreasure = $this->treasureModel->getUserCurrentTreasure($userId);
            $treasureLeaderboard = [];
            
            if ($currentTreasure) {
                $treasureLeaderboard = $this->attemptModel->getTreasureLeaderboard($currentTreasure['id'], 5);
            }

            // Get user's progress on current treasure
            $userProgress = null;
            if ($currentTreasure) {
                $userProgress = $this->progressModel->getUserProgress($userId, $currentTreasure['id']);
            }

            $data = [
                'title' => 'Treasure Hunt Indonesia',
                'currentTreasure' => $currentTreasure,
                'userProgress' => $userProgress,
                'treasureLeaderboard' => $treasureLeaderboard,
                'globalLeaderboard' => $this->playerModel->getLeaderboard(5),
                'isLoggedIn' => session()->get('is_logged_in'),
                'currentUser' => session()->get('full_name')
            ];

            return view('game/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Game index error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'An error occurred loading the game. Please try again.');
        }
    }

    public function options()
    {
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With')
            ->setStatusCode(200);
    }

    public function check()
    {
        // Require authentication to make guesses
        if (!session()->get('is_logged_in')) {
            return $this->response->setJSON(['error' => 'Please login to play the game']);
        }

        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');

        if ($this->request->getMethod() === 'OPTIONS') {
            return $this->response->setStatusCode(200);
        }

        // Start database transaction for consistency
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $input = $this->request->getJSON(true);
            if (!$input) {
                $input = $this->request->getPost();
            }
            
            if (!isset($input['latitude']) || !isset($input['longitude'])) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'Missing coordinates']);
            }
            
            $guessLat = floatval($input['latitude']);
            $guessLng = floatval($input['longitude']);
            
            if ($guessLat < -90 || $guessLat > 90 || $guessLng < -180 || $guessLng > 180) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'Invalid coordinates']);
            }

            $userId = session()->get('user_id');
            $playerName = session()->get('full_name');
            
            // Check if user exists
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            if (!$user) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'User not found']);
            }
            
            // Initialize current_treasure_index if not set
            if (!isset($user['current_treasure_index']) || $user['current_treasure_index'] === null) {
                $userModel->update($userId, ['current_treasure_index' => 1]);
                $user['current_treasure_index'] = 1;
            }
            
            $player = $this->playerModel->getOrCreatePlayerForUser($userId, $playerName, session()->get('email'));
            
            if (!$player) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'Failed to create or find player record']);
            }
            
            $treasure = $this->treasureModel->getUserCurrentTreasure($userId);
            
            if (!$treasure) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'Congratulations! You have completed all treasures in Indonesia! 🏆']);
            }

            $distance = $this->treasureModel->calculateDistance(
                $guessLat, $guessLng,
                $treasure['latitude'], $treasure['longitude']
            );
            
            if (!is_finite($distance) || $distance < 0) {
                $db->transRollback();
                return $this->response->setJSON(['error' => 'Distance calculation failed']);
            }

            $pointsEarned = 0;
            $isSuccess = false;
            $gameStatus = 'continue';
            $treasureCompleted = false;
            $nextTreasure = null;

            // Define distance thresholds
            $perfectRadius = 100;   // Within 100m = Perfect find (COMPLETE TREASURE)
            $closeRadius = 500;     // Within 500m = Very close (good points)
            $hintRadius = 2000;     // Within 2km = Close enough for hints

            // Treasure completion logic
            if ($distance <= $perfectRadius) {
                // PERFECT FIND - Complete this treasure and move to next
                $isSuccess = true;
                $gameStatus = 'success';
                $treasureCompleted = true;
                $accuracy = 1 - ($distance / $perfectRadius);
                $pointsEarned = max(80, intval($treasure['points'] * $accuracy));
                
            } elseif ($distance <= $closeRadius) {
                // VERY CLOSE - Give good points, but game continues
                $gameStatus = 'very_close';
                $accuracy = 1 - ($distance / $closeRadius);
                $pointsEarned = max(30, intval(60 * $accuracy));
                
            } elseif ($distance <= $hintRadius) {
                // CLOSE - Give small points and hints
                $gameStatus = 'close';
                $pointsEarned = max(5, intval(20 * (1 - $distance / $hintRadius)));
            }

            // Update player score (with error handling)
            $scoreUpdated = false;
            if ($pointsEarned > 0) {
                $scoreUpdated = $this->playerModel->updateScore($player['id'], $pointsEarned);
                if (!$scoreUpdated) {
                    log_message('error', 'Failed to update player score');
                }
            }

            // Update user's progress on this treasure (with error handling)
            $progressUpdated = $this->progressModel->updateProgress($userId, $treasure['id'], $distance, $pointsEarned, $isSuccess);
            if (!$progressUpdated) {
                log_message('error', 'Failed to update user progress');
            }

            // Record attempt (with error handling)
            try {
                $this->attemptModel->insert([
                    'player_id' => $player['id'],
                    'treasure_id' => $treasure['id'],
                    'guess_latitude' => $guessLat,
                    'guess_longitude' => $guessLng,
                    'distance' => $distance,
                    'points_earned' => $pointsEarned,
                    'is_success' => $isSuccess
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to record attempt: ' . $e->getMessage());
            }

            // If treasure is completed, get next treasure info BEFORE committing transaction
            if ($treasureCompleted) {
                $nextTreasure = $this->treasureModel->getNextTreasureForUser($userId);
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Database transaction failed');
                return $this->response->setJSON(['error' => 'Database error occurred']);
            }

            $message = $this->getDistanceMessage($distance, $pointsEarned, $gameStatus, $treasure['name']);
            
            // Add next treasure info if current one is completed
            if ($treasureCompleted && $nextTreasure) {
                $message .= " 🎯 Next treasure: " . $nextTreasure['name'] . " is now active for you!";
            } elseif ($treasureCompleted && !$nextTreasure) {
                $message .= " 🏆 Congratulations! You've completed all treasures in Indonesia!";
            }

            return $this->response->setJSON([
                'success' => $isSuccess,
                'gameStatus' => $gameStatus,
                'distance' => round($distance, 0),
                'points' => $pointsEarned,
                'message' => $message,
                'hint' => $this->getHint($distance, $gameStatus),
                'treasureCompleted' => $treasureCompleted,
                'nextTreasure' => $nextTreasure,
                'treasureLocation' => $isSuccess ? [
                    'lat' => floatval($treasure['latitude']),
                    'lng' => floatval($treasure['longitude']),
                    'name' => $treasure['name']
                ] : null,
                'debug' => [
                    'distance' => $distance,
                    'perfectRadius' => $perfectRadius,
                    'isWithinPerfectRadius' => $distance <= $perfectRadius,
                    'treasureCompleted' => $treasureCompleted,
                    'progressUpdated' => $progressUpdated ?? false,
                    'scoreUpdated' => $scoreUpdated ?? false,
                    'treasureData' => $treasure
                ]
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Game check error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
            return $this->response->setJSON([
                'error' => 'Server error occurred: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    public function treasures()
    {
        try {
            $userId = session()->get('user_id');
            $treasures = [];
            
            if ($userId) {
                // Show user-specific progress
                $treasures = $this->treasureModel->getAllTreasuresWithUserProgress($userId);
            } else {
                // Show all treasures without progress for guests
                $treasures = $this->treasureModel->where('is_active', 1)->orderBy('id', 'ASC')->findAll();
                // Add empty progress for consistency
                foreach ($treasures as &$treasure) {
                    $treasure['user_progress'] = null;
                }
            }

            $data = [
                'title' => 'All Treasures - Indonesia',
                'treasures' => $treasures,
                'isLoggedIn' => session()->get('is_logged_in')
            ];

            return view('game/treasures', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Treasures page error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'An error occurred loading treasures. Please try again.');
        }
    }

    public function treasureLeaderboard($treasureId)
    {
        try {
            // Validate treasure ID
            if (!is_numeric($treasureId) || $treasureId <= 0) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid treasure ID');
            }

            $treasure = $this->treasureModel->find($treasureId);
            if (!$treasure) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Treasure not found');
            }

            // Get leaderboard from attempts table (not user_treasure_progress)
            $leaderboard = $this->attemptModel->getTreasureLeaderboard($treasureId, 20);

            $data = [
                'title' => 'Leaderboard - ' . $treasure['name'],
                'treasure' => $treasure,
                'leaderboard' => $leaderboard
            ];

            return view('game/treasure-leaderboard', $data);
            
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e; // Re-throw 404 errors
        } catch (\Exception $e) {
            log_message('error', 'Treasure leaderboard error: ' . $e->getMessage());
            return redirect()->to('/game/treasures')->with('error', 'An error occurred loading the leaderboard.');
        }
    }

    public function myProgress()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth/login');
        }

        try {
            $userId = session()->get('user_id');
            $treasures = $this->treasureModel->getAllTreasuresWithUserProgress($userId);
            $stats = $this->progressModel->getUserTreasureStats($userId);

            $data = [
                'title' => 'My Treasure Progress',
                'treasures' => $treasures,
                'stats' => $stats
            ];

            return view('game/my-progress', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'My progress error: ' . $e->getMessage());
            return redirect()->to('/game')->with('error', 'An error occurred loading your progress.');
        }
    }

    private function getDistanceMessage($distance, $points, $status, $treasureName)
    {
        $distanceFormatted = number_format($distance, 0);
        
        switch ($status) {
            case 'success':
                $messages = [
                    "🎉 {$treasureName} DITEMUKAN! Perfect! +{$points} poin!",
                    "🏆 SELAMAT! {$treasureName} found perfectly! +{$points} points!",
                    "🎊 LUAR BIASA! You conquered {$treasureName}! +{$points} points!"
                ];
                break;
                
            case 'very_close':
                $messages = [
                    "🔥 SANGAT DEKAT ke {$treasureName}! Jarak: {$distanceFormatted}m. +{$points} poin!",
                    "🎯 VERY CLOSE to {$treasureName}! Distance: {$distanceFormatted}m. +{$points} points!",
                    "⭐ HAMPIR KETEMU {$treasureName}! You're getting warmer! +{$points} points!"
                ];
                break;
                
            case 'close':
                $messages = [
                    "👍 Lumayan dekat ke {$treasureName}! Jarak: {$distanceFormatted}m. +{$points} poin!",
                    "🧭 Getting closer to {$treasureName}! Distance: {$distanceFormatted}m. +{$points} points!",
                    "📍 You're in the right area near {$treasureName}! +{$points} points!"
                ];
                break;
                
            default:
                $messages = [
                    "❌ Masih jauh dari {$treasureName}! Jarak: {$distanceFormatted}m. Coba lagi!",
                    "🗺️ Too far from {$treasureName}! Distance: {$distanceFormatted}m. Keep exploring!",
                    "🧭 Not quite there yet! {$distanceFormatted}m from {$treasureName}. Try again!"
                ];
        }
        
        return $messages[array_rand($messages)];
    }

    private function getHint($distance, $status)
    {
        switch ($status) {
            case 'success':
                return "🏆 Congratulations! You found the treasure!";
                
            case 'very_close':
                if ($distance < 50) {
                    return "🔥 You're EXTREMELY close! The treasure is right here!";
                } elseif ($distance < 200) {
                    return "🔥 You're VERY close! Look around this exact area!";
                } else {
                    return "🎯 Almost there! The treasure is within a few hundred meters!";
                }
                
            case 'close':
                return "📍 You're in the right neighborhood! The treasure is nearby.";
                
            default:
                if ($distance > 10000) {
                    return "🌍 You're on the wrong island! Try a different region.";
                } elseif ($distance > 5000) {
                    return "🗺️ Wrong city! Look for a different area in Indonesia.";
                } else {
                    return "🧭 Keep exploring! You need to get much closer.";
                }
        }
    }

    public function leaderboard()
    {
        try {
            $data = [
                'title' => 'Global Leaderboard',
                'leaderboard' => $this->playerModel->getLeaderboard(20),
                'recentAttempts' => $this->attemptModel->getRecentAttempts(10)
            ];

            return view('game/leaderboard', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Leaderboard error: ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'An error occurred loading the leaderboard.');
        }
    }

    public function reset()
    {
        return $this->response->setJSON(['success' => true, 'message' => 'Game reset!']);
    }

    public function test()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'API is working!',
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $this->request->getMethod()
        ]);
    }
}
