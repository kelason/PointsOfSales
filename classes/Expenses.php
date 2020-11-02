<?php

class Expenses extends Database
{
    public $id;
    public $cashier_id;
    public $total_amount;
    public $expense_note;
    public $iscancel;
    public $created_at;
    public $from_date;
    public $to_date;
    private $table = "posexpenses";
    private $tableEmp = "posemployees";
    
    public function createExpense() {
        $fields = array(
            'total_amount'  => $this->total_amount,
            'cashier_id'  => $this->cashier_id,
            'expense_note'  => $this->expense_note,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function getAllExpenses() {
        $query = "SELECT
            a.id AS ex_id,
            a.total_amount,
            a.expense_note,
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

    public function cancelExpense() {
        $query = "UPDATE $this->table SET iscancel=:iscancel WHERE id=:id";

        $fields = array(
            'iscancel'  => $this->iscancel,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}