<?php

class Customers extends Database
{
    public $id;
    public $payment_name;
    public $customer_phone;
    public $customer_status;
    private $table = "poscustomers";
    
    public function getAllCustomers() {
        $query = "SELECT id, customer_name, customer_phone, customer_status FROM $this->table";

        $result = $this->setRows($query);
        return $result;
    }

    public function getAllCustomersbyStatus() {
        $query = "SELECT id, customer_name, customer_phone, customer_status FROM $this->table WHERE customer_status=?";
        $params = [$this->customer_status];

        $result = $this->setRows($query, $params);
        return $result;
    }

    public function addCustomer() {
        $fields = array(
            'customer_name'  => $this->customer_name,
            'customer_phone'  => $this->customer_phone,
            'customer_status'  => $this->customer_status,
        );

        return $this->create($this->table, $fields);
    }

    public function updateCustomer() {
        $query = "UPDATE $this->table 
        SET 
            customer_name=:customer_name, 
            customer_phone=:customer_phone, 
            customer_status=:customer_status
        WHERE 
            id=:id";

        $fields = array(
            'customer_name'  => $this->customer_name,
            'customer_phone'  => $this->customer_phone,
            'customer_status'  => $this->customer_status,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}