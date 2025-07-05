<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PlayerModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $playerModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->playerModel = new PlayerModel();
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'full_name' => $this->request->getPost('full_name')
            ];

            if ($this->userModel->validate($data)) {
                $userId = $this->userModel->createUser($data);
                
                if ($userId) {
                    // Create associated player record
                    $this->playerModel->getOrCreatePlayerForUser($userId, $data['full_name'], $data['email']);
                    
                    // Auto login after registration
                    $user = $this->userModel->find($userId);
                    $this->setUserSession($user);
                    
                    return redirect()->to('/game')->with('success', 'Account created successfully! Welcome to Treasure Hunt Indonesia!');
                }
            }
            
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return view('auth/register');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->userModel->verifyUser($username, $password);
            
            if ($user) {
                $this->setUserSession($user);
                return redirect()->to('/game')->with('success', 'Welcome back, ' . $user['full_name'] . '!');
            }
            
            return redirect()->back()->withInput()->with('error', 'Invalid username/email or password');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out successfully');
    }

    public function profile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserWithStats($userId);
        
        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];

        return view('auth/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        if ($this->request->getMethod() === 'POST') {
            $userId = session()->get('user_id');
            $data = [
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email')
            ];

            if ($this->userModel->update($userId, $data)) {
                return redirect()->to('/auth/profile')->with('success', 'Profile updated successfully!');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return redirect()->to('/auth/profile');
    }

    private function setUserSession($user)
    {
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'is_logged_in' => true
        ]);
    }
}
