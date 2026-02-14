<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/admin/login.php';
    }

    // Method to handle displaying all users
    public function listUsers()
    {
        $users = $this->userModel->readAll();
        require_once __DIR__ . '/../views/user_list.php'; // Pass data to the view for rendering
    }

    // Method to handle creating a user
    public function createUser($username, $email, $password)
    {
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->password_hash = $password;

        if ($this->userModel->create()) {
            echo "User created successfully.";
        } else {
            echo "Error creating user.";
        }
    }

    // Method to handle viewing a specific user by ID
    public function viewUser($id)
    {
        $user = $this->userModel->findById($id);
        if ($user) {
            print_r($user);
        } else {
            echo "User not found.";
        }
    }

    public function login($email, $password)
    {
        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
    }

    public function logout() {}
}
