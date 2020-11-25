<?php

class Users extends Database
{
    public $id;
    public $username;
    public $employee_id;
    public $userpassword;
    public $user_status;
    public $user_approval;
    public $created_at;
    private $table = "posusers";
    private $tableEmp = "posemployees";

    public function createUser()
    {
        $fields = array(
            'username'  => $this->username,
            'employee_id'  => $this->employee_id,
            'userpassword'  => $this->encrypt($this->username),
            'user_approval'  => $this->user_approval,
            'user_status'  => $this->user_status,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function updatePassword()
    {
        $query = "UPDATE $this->table
        SET
            userpassword=:userpassword
        WHERE
            id=:id";
        $fields = array(
            'userpassword'  => $this->encrypt($this->userpassword),
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    private function encrypt($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function existUser() {
        $query = "SELECT username FROM $this->table WHERE username=?";
        $params = [$this->username];

		$result = $this->setRowCount($query, $params);
		return $result;
    }

    public function getPassword()
	{
        $query = "SELECT userpassword FROM $this->table WHERE username=? AND user_status=?";
        $params = [$this->username, $this->user_status];

		$result = $this->setColumn($query, $params);
		return $result;
    }

    public function getPasswordById()
	{
        $query = "SELECT userpassword FROM $this->table WHERE id=?";
        $params = [$this->id];

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
    
    public function getAllUsers()
	{
        $query = "SELECT 
            a.username, 
            a.user_approval, 
            a.user_status, 
            b.employee_fn, 
            b.employee_mn, 
            b.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tableEmp AS b ON b.id=a.employee_id";

		$result = $this->setRows($query);
		return $result;
	}
}