<?php

class Customers extends Database
{
    public $id;
    public $payment_name;
    public $customer_phone;
    public $customer_status;
    private $table = "poscustomers";
    
    public function getAllCustomers() {
        $query = "SELECT id, customer_name, customer_phone FROM $this->table WHERE customer_status=?";
        $params = [$this->customer_status];

        $result = $this->setRows($query, $params);
        return $result;
    }
}