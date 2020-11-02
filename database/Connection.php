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
        try{
            $this->conn = new PDO("CSyNgV");
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