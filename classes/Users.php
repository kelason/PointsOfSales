<?php

class Users extends Database
{
    public $id;
    public $username;
    public $userpassword;
    public $created_at;
    private $table = "posusers";

    public function createUser()
    {
        $fields = array(
            'username'  => $this->username,
            'userpassword'  => $this->encrypt(),
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    private function encrypt()
    {
        return password_hash($this->userpassword, PASSWORD_BCRYPT);
    }

    public function getPassword()
	{
        $query = "SELECT userpassword FROM $this->table WHERE username=?";
        $params = [$this->username];

		$result = $this->setColumn($query, $params);
		return $result;
    }
    
    public function getUserID()
	{
        $query = "SELECT employee_id FROM $this->table WHERE username=?";
        $params = [$this->username];

		$result = $this->setColumn($query, $params);
		return $result;
	}
}