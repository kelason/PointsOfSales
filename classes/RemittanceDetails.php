<?php

class RemittanceDetails extends Database
{
    public $id;
    public $remit_id;
    public $remit_qty;
    public $remit_amount;
    public $remit_total;
    public $fields;
    private $table = "posremittance_details";

    public function createExpenseDetails() {
        return $this->createMany($this->table, $this->fields);
    }

    public function getAllRemittanceDetailsById() {
        $query = "SELECT 
            id, 
            remit_qty,
            remit_amount,
            remit_total
        FROM $this->table
        WHERE remit_id=?";
        $params = [$this->remit_id];
        $result = $this->setRows($query, $params);
        return $result;
    }
}