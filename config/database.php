<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    private $con;

    public function __construct()
    {
        // Use environment variables loaded from .env file
        $host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $port = getenv('DB_PORT');

        try {
            $this->con = new PDO(
                "mysql:host=$host;dbname=$db_name;port=$port",
                $username,
                $password
            );
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->con;
    }
}
