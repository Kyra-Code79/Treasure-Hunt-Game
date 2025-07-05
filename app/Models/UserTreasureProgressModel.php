<?php

namespace App\Models;

use CodeIgniter\Model;

class UserTreasureProgressModel extends Model
{
    protected $table = 'user_treasure_progress';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'treasure_id', 'is_completed', 'completed_at', 'best_distance', 'best_points', 'total_attempts'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getUserProgress($userId, $treasureId)
    {
        try {
            return $this->where('user_id', $userId)
                       ->where('treasure_id', $treasureId)
                       ->first();
        } catch (\Exception $e) {
            log_message('error', 'getUserProgress error: ' . $e->getMessage());
            return null;
        }
    }

    public function getOrCreateProgress($userId, $treasureId)
    {
        try {
            $progress = $this->getUserProgress($userId, $treasureId);
            
            if (!$progress) {
                $progressId = $this->insert([
                    'user_id' => $userId,
                    'treasure_id' => $treasureId,
                    'is_completed' => false,
                    'best_distance' => null,
                    'best_points' => 0,
                    'total_attempts' => 0
                ]);
                
                if ($progressId) {
                    return $this->find($progressId);
                } else {
                    log_message('error', 'Failed to create progress record for user ' . $userId . ', treasure ' . $treasureId);
                    return null;
                }
            }
            
            return $progress;
        } catch (\Exception $e) {
            log_message('error', 'getOrCreateProgress error: ' . $e->getMessage());
            return null;
        }
    }

    public function updateProgress($userId, $treasureId, $distance, $points, $isSuccess = false)
    {
        try {
            $progress = $this->getOrCreateProgress($userId, $treasureId);
            
            if (!$progress) {
                log_message('error', 'Could not get or create progress record');
                return false;
            }

            $updateData = [
                'total_attempts' => $progress['total_attempts'] + 1
            ];

            // Update best distance if this is better
            if ($progress['best_distance'] === null || $distance < $progress['best_distance']) {
                $updateData['best_distance'] = $distance;
            }

            // Update best points if this is better
            if ($points > $progress['best_points']) {
                $updateData['best_points'] = $points;
            }

            // Mark as completed if successful
            if ($isSuccess && !$progress['is_completed']) {
                $updateData['is_completed'] = true;
                $updateData['completed_at'] = date('Y-m-d H:i:s');
            }

            // Only update if we have data to update
            if (!empty($updateData)) {
                $result = $this->update($progress['id'], $updateData);
                if (!$result) {
                    log_message('error', 'Failed to update progress record: ' . json_encode($updateData));
                }
                return $result;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'updateProgress error: ' . $e->getMessage());
            return false;
        }
    }

    public function getUserCompletedTreasures($userId)
    {
        try {
            return $this->where('user_id', $userId)
                       ->where('is_completed', true)
                       ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'getUserCompletedTreasures error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getUserTreasureStats($userId)
    {
        try {
            return $this->select('
                            COUNT(*) as total_treasures_attempted,
                            SUM(is_completed) as treasures_completed,
                            SUM(total_attempts) as total_attempts,
                            AVG(best_distance) as avg_best_distance,
                            SUM(best_points) as total_best_points
                        ')
                       ->where('user_id', $userId)
                       ->first();
        } catch (\Exception $e) {
            log_message('error', 'getUserTreasureStats error: ' . $e->getMessage());
            return [
                'total_treasures_attempted' => 0,
                'treasures_completed' => 0,
                'total_attempts' => 0,
                'avg_best_distance' => 0,
                'total_best_points' => 0
            ];
        }
    }

    public function getTreasureLeaderboard($treasureId, $limit = 10)
    {
        try {
            return $this->select('user_treasure_progress.*, users.username, users.full_name')
                       ->join('users', 'users.id = user_treasure_progress.user_id')
                       ->where('treasure_id', $treasureId)
                       ->where('is_completed', true)
                       ->orderBy('best_distance', 'ASC')
                       ->orderBy('completed_at', 'ASC')
                       ->limit($limit)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getTreasureLeaderboard error: ' . $e->getMessage());
            return [];
        }
    }
}
