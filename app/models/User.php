<?php

namespace App\Models;

use Config\Database;
use PDO;

class User
{
    private $con;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password_hash;

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    // Method to create a new user
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, email=:email, password_hash=:password_hash";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);

        // Execute the query
        return $stmt->execute();
    }

    // Method to fetch all users
    public function readAll()
    {
        $query = "SELECT id, username, email FROM " . $this->table_name;
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to find a user by ID
    public function findById($id)
    {
        $query = "SELECT id, username, email FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to find a user by username
    public function findByEmail($email)
    {
        $query = "SELECT id, username, email FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to find a user by username
    public function findByUsername($username)
    {
        $query = "SELECT id, username, email FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
