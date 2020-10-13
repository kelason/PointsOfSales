<?php

class Connection
{
    private $host;
    private $dbname;
    private $username;
    private $password;

    private $conn;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;

        try{
            $this->conn = new PDO("mysql:host={$this->host};  dbname={$this->dbname}; charset=utf8", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }catch(PDOException $e){
			throw new Exception($e->getMessage());			
		}
    }

    public function getConnection()
    {
        return $this->conn;
    }
}