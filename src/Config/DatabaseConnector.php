<?php
namespace src\Config;

class DatabaseConnector {

    private $dbConnection = null;

    public function __construct()
    {
        $host = "mysql-box";
        $port = 3306;
        $db   = 'lovebinto';
        $user = "root";
        $pass = "example";

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}