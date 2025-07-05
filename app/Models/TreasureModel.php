<?php

namespace App\Models;

use CodeIgniter\Model;

class TreasureModel extends Model
{
    protected $table = 'treasures';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'latitude', 'longitude', 'radius', 'points', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getUserCurrentTreasure($userId)
    {
        try {
            // Get user's current treasure index
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            
            if (!$user) {
                return null;
            }

            // Ensure current_treasure_index is set
            $currentIndex = $user['current_treasure_index'] ?? 1;

            // Get all active treasures ordered by ID
            $treasures = $this->where('is_active', 1)
                             ->orderBy('id', 'ASC')
                             ->findAll();

            // Return the treasure at the current index (1-based)
            if (isset($treasures[$currentIndex - 1])) {
                return $treasures[$currentIndex - 1];
            }

            return null;
        } catch (\Exception $e) {
            log_message('error', 'getUserCurrentTreasure error: ' . $e->getMessage());
            return null;
        }
    }

    public function getNextTreasureForUser($userId)
    {
        try {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            
            if (!$user) {
                return null;
            }

            // Get all active treasures
            $treasures = $this->where('is_active', 1)
                             ->orderBy('id', 'ASC')
                             ->findAll();

            $currentIndex = $user['current_treasure_index'] ?? 1;
            $nextIndex = $currentIndex + 1;

            // Check if there's a next treasure
            if (isset($treasures[$nextIndex - 1])) {
                // Update user's current treasure index
                $updateResult = $userModel->update($userId, ['current_treasure_index' => $nextIndex]);
                
                if ($updateResult) {
                    return $treasures[$nextIndex - 1];
                } else {
                    log_message('error', 'Failed to update user current_treasure_index for user ' . $userId);
                    return null;
                }
            }

            // No more treasures
            return null;
        } catch (\Exception $e) {
            log_message('error', 'getNextTreasureForUser error: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllTreasuresWithUserProgress($userId)
    {
        try {
            $progressModel = new \App\Models\UserTreasureProgressModel();
            
            $treasures = $this->where('is_active', 1)
                             ->orderBy('id', 'ASC')
                             ->findAll();

            // Add progress information for each treasure
            foreach ($treasures as &$treasure) {
                $progress = $progressModel->getUserProgress($userId, $treasure['id']);
                $treasure['user_progress'] = $progress;
                $treasure['is_completed_by_user'] = $progress ? $progress['is_completed'] : false;
            }

            return $treasures;
        } catch (\Exception $e) {
            log_message('error', 'getAllTreasuresWithUserProgress error: ' . $e->getMessage());
            return [];
        }
    }

    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        try {
            if (!is_numeric($lat1) || !is_numeric($lon1) || !is_numeric($lat2) || !is_numeric($lon2)) {
                return 0;
            }
            
            $lat1 = floatval($lat1);
            $lon1 = floatval($lon1);
            $lat2 = floatval($lat2);
            $lon2 = floatval($lon2);
            
            if ($lat1 < -90 || $lat1 > 90 || $lat2 < -90 || $lat2 > 90) {
                return 0;
            }
            if ($lon1 < -180 || $lon1 > 180 || $lon2 < -180 || $lon2 > 180) {
                return 0;
            }
            
            if ($lat1 == $lat2 && $lon1 == $lon2) {
                return 0;
            }
            
            $earthRadius = 6371000; // Earth's radius in meters
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat/2) * sin($dLat/2) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                 sin($dLon/2) * sin($dLon/2);

            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $distance = $earthRadius * $c;

            return is_finite($distance) && $distance >= 0 ? $distance : 0;
        } catch (\Exception $e) {
            log_message('error', 'calculateDistance error: ' . $e->getMessage());
            return 0;
        }
    }
}
