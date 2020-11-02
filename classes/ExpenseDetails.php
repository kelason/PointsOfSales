<?php

class ExpenseDetails extends Database
{
    public $id;
    public $expense_id;
    public $particulars_id;
    public $expense_amount;
    public $fields;
    private $table = "posexpense_details";
    private $tablePar = "posparticulars";

    public function createExpenseDetails() {
        return $this->createMany($this->table, $this->fields);
    }

    public function getAllExpenseDetailsById() {
        $query = "SELECT 
            a.id, 
            a.expense_amount,
            b.particulars_name
        FROM $this->table AS a 
        INNER JOIN $this->tablePar AS b ON b.id=a.particulars_id
        WHERE a.expense_id=?";
        $params = [$this->id];
        $result = $this->setRows($query, $params);
        return $result;
    }
}