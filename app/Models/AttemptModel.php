<?php

namespace App\Models;

use CodeIgniter\Model;

class AttemptModel extends Model
{
    protected $table = 'attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['player_id', 'treasure_id', 'guess_latitude', 'guess_longitude', 'distance', 'points_earned', 'is_success'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPlayerAttempts($playerId, $limit = 10)
    {
        return $this->where('player_id', $playerId)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getRecentAttempts($limit = 20)
    {
        return $this->select('attempts.*, players.name as player_name, treasures.name as treasure_name')
                   ->join('players', 'players.id = attempts.player_id')
                   ->join('treasures', 'treasures.id = attempts.treasure_id')
                   ->orderBy('attempts.created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getTreasureLeaderboard($treasureId, $limit = 20)
    {
        try {
            // Get the best attempt for each player for this treasure
            return $this->select('
                        attempts.*,
                        players.name as player_name,
                        users.username,
                        users.full_name,
                        MIN(attempts.distance) as best_distance
                    ')
                    ->join('players', 'players.id = attempts.player_id')
                    ->join('users', 'users.id = players.user_id', 'left')
                    ->where('attempts.treasure_id', $treasureId)
                    ->groupBy('attempts.player_id')
                    ->orderBy('best_distance', 'ASC')
                    ->orderBy('attempts.points_earned', 'DESC')
                    ->orderBy('attempts.created_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'getTreasureLeaderboard error: ' . $e->getMessage());
            return [];
        }
    }

    public function getTreasureAttemptCount($treasureId)
    {
        try {
            return $this->where('treasure_id', $treasureId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'getTreasureAttemptCount error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTreasureSuccessCount($treasureId)
    {
        try {
            return $this->where('treasure_id', $treasureId)
                       ->where('is_success', true)
                       ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'getTreasureSuccessCount error: ' . $e->getMessage());
            return 0;
        }
    }
}
