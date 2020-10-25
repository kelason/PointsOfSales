<?php

class PaymentTypes extends Database
{
    public $id;
    public $payment_name;
    private $table = "pospayment_types";
    
    public function getAllPaymentTypes() {
        $query = "SELECT id, payment_name FROM $this->table";

        $result = $this->setRows($query);
        return $result;
    }
}