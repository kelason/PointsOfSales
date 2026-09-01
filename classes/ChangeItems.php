<?php

class ChangeItems extends Database
{
    public $id;
    public $created_at;
    public $cashier_id;
    public $customer_id;
    public $order_id;
    public $changeitem_note;
    public $iscancel;
    public $additional_amount;
    public $from_date;
    public $to_date;
    private $table = "poschangeitems";
    private $tableEmp = "posemployees";

    public function createChangeItem() {
        $fields = array(
            'cashier_id'  => $this->cashier_id,
            'changeitem_note'  => $this->changeitem_note,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function getAllChangeItems() {
        $query = "SELECT
            a.id,
            a.changeitem_note,
            a.iscancel,
            a.created_at,
            b.employee_fn,
            b.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tableEmp AS b ON b.id=a.cashier_id
        WHERE a.created_at>=?
        AND a.created_at<=?";

        $params = [$this->from_date, $this->to_date];

        return $this->setRows($query, $params);
    }

    public function getChangeItemById() {
        $query = "SELECT
            a.id,
            a.changeitem_note,
            a.iscancel,
            a.created_at,
            b.employee_fn,
            b.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tableEmp AS b ON b.id=a.cashier_id
        WHERE a.id=?";

        $params = [$this->id];

        return $this->setRow($query, $params);
    }

    public function cancelChangeItem() {
        $query = "UPDATE $this->table SET iscancel=:iscancel WHERE id=:id";

        $fields = array(
            'iscancel'  => $this->iscancel,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}
