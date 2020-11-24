<?php

class Remittances extends Database
{
    public $id;
    public $cashier_id;
    public $expense_amount;
    public $sales_amount;
    public $remitted_amount;
    public $created_at;
    public $iscancel;
    public $from_date;
    public $to_date;
    private $table = "posremittances";
    private $tableEmp = "posemployees";
    
    public function createRemittance() {
        $fields = array(
            'expense_amount'  => $this->expense_amount,
            'cashier_id'  => $this->cashier_id,
            'sales_amount'  => $this->sales_amount,
            'remitted_amount'  => $this->remitted_amount,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function getAllRemittances() {
        $query = "SELECT
            a.id AS remit_id,
            a.expense_amount,
            a.sales_amount,
            a.remitted_amount,
            a.iscancel,
            a.created_at,
            b.employee_fn,
            b.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tableEmp AS b ON b.id=a.cashier_id
        WHERE DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?";

        $params = [$this->from_date, $this->to_date];
        return $this->setrows($query, $params);
    }

    public function getAllRemittancesById() {
        $query = "SELECT
            a.id,
            a.created_at,
            a.iscancel,
            b.employee_fn,
            b.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tableEmp AS b ON b.id=a.cashier_id
        WHERE a.id=?";

        $params = [$this->id];
        return $this->setrows($query, $params);
    }

    public function cancelRemittance() {
        $query = "UPDATE $this->table SET iscancel=:iscancel WHERE id=:id";

        $fields = array(
            'iscancel'  => $this->iscancel,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}