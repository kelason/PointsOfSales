<?php

class Users extends Database
{
    public $id;
    public $username;
    public $userpassword;
    public $user_status;
    public $user_approval;
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

    public function getUserApproval()
	{
        $query = "SELECT user_approval FROM $this->table WHERE username=? AND user_status=?";
        $params = [$this->username, $this->user_status];

		$result = $this->setColumn($query, $params);
		return $result;
	}
    
    public function getUserID()
	{
        $query = "SELECT employee_id FROM $this->table WHERE username=? AND user_status=?";
        $params = [$this->username, $this->user_status];

		$result = $this->setColumn($query, $params);
		return $result;
	}
}