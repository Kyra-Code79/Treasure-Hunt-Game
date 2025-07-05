<?php

namespace App\Models;

use CodeIgniter\Model;

class PlayerModel extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'name', 'email', 'total_score', 'games_played'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getOrCreatePlayerForUser($userId, $name, $email = null)
    {
        try {
            // First try to find existing player for this user
            $player = $this->where('user_id', $userId)->first();
            
            if (!$player) {
                // Create new player linked to user
                $playerId = $this->insert([
                    'user_id' => $userId,
                    'name' => $name,
                    'email' => $email,
                    'total_score' => 0,
                    'games_played' => 0
                ]);
                
                if ($playerId) {
                    return $this->find($playerId);
                } else {
                    log_message('error', 'Failed to create player for user ' . $userId);
                    return null;
                }
            }
            
            return $player;
        } catch (\Exception $e) {
            log_message('error', 'getOrCreatePlayerForUser error: ' . $e->getMessage());
            return null;
        }
    }

    public function getOrCreatePlayer($name, $email = null)
    {
        try {
            // Legacy method for non-authenticated users
            $player = $this->where('name', $name)->where('user_id', null)->first();
            
            if (!$player) {
                $playerId = $this->insert([
                    'user_id' => null,
                    'name' => $name,
                    'email' => $email,
                    'total_score' => 0,
                    'games_played' => 0
                ]);
                
                if ($playerId) {
                    return $this->find($playerId);
                } else {
                    log_message('error', 'Failed to create anonymous player');
                    return null;
                }
            }
            
            return $player;
        } catch (\Exception $e) {
            log_message('error', 'getOrCreatePlayer error: ' . $e->getMessage());
            return null;
        }
    }

    public function updateScore($playerId, $points)
    {
        try {
            $player = $this->find($playerId);
            if ($player) {
                $updateData = [
                    'total_score' => $player['total_score'] + $points,
                    'games_played' => $player['games_played'] + 1
                ];
                
                $result = $this->update($playerId, $updateData);
                if (!$result) {
                    log_message('error', 'Failed to update player score for player ' . $playerId);
                }
                return $result;
            } else {
                log_message('error', 'Player not found for score update: ' . $playerId);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'updateScore error: ' . $e->getMessage());
            return false;
        }
    }

    public function getLeaderboard($limit = 10)
    {
        try {
            return $this->select('players.*, users.username, users.full_name, users.avatar_url')
                       ->join('users', 'users.id = players.user_id', 'left')
                       ->orderBy('players.total_score', 'DESC')
                       ->limit($limit)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getLeaderboard error: ' . $e->getMessage());
            return [];
        }
    }
}
