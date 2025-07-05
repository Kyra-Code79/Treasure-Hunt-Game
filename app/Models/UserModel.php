<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password_hash', 'full_name', 'avatar_url', 'is_active', 'email_verified', 'current_treasure_index'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password_hash' => 'required|min_length[8]',
        'full_name' => 'required|min_length[2]|max_length[100]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters',
            'is_unique' => 'Username already exists'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email',
            'is_unique' => 'Email already registered'
        ]
    ];

    public function createUser($data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['current_treasure_index'] = 1; // Start with first treasure
        unset($data['password']);
        
        return $this->insert($data);
    }

    public function verifyUser($username, $password)
    {
        $user = $this->where('username', $username)
                    ->orWhere('email', $username)
                    ->where('is_active', 1)
                    ->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    public function getUserWithStats($userId)
    {
        return $this->select('users.*, 
                            COALESCE(players.total_score, 0) as total_score,
                            COALESCE(players.games_played, 0) as games_played,
                            COUNT(DISTINCT treasures.id) as treasures_found')
                   ->join('players', 'players.user_id = users.id', 'left')
                   ->join('treasures', 'treasures.completed_by = players.id', 'left')
                   ->where('users.id', $userId)
                   ->groupBy('users.id')
                   ->first();
    }

    public function getTopUsers($limit = 10)
    {
        return $this->select('users.*, 
                            COALESCE(players.total_score, 0) as total_score,
                            COALESCE(players.games_played, 0) as games_played,
                            COUNT(DISTINCT treasures.id) as treasures_found')
                   ->join('players', 'players.user_id = users.id', 'left')
                   ->join('treasures', 'treasures.completed_by = players.id', 'left')
                   ->groupBy('users.id')
                   ->orderBy('total_score', 'DESC')
                   ->orderBy('treasures_found', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
