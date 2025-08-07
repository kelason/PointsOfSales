<?php
class Connection
{
    // For Docker-to-Docker communication (within same network)
    private $host = "db";           // Use the service name from docker-compose.yml
    private $dbname = "php_pos";
    private $username = "php_pos";
    private $password = "php_pos";
    private $port = "3306";         // Use container port (3306), not external mapped port

    private $conn;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}