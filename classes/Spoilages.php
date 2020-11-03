<?php

class Spoilages extends Database
{
    public $id;
    public $created_at;
    public $cashier_id;
    public $iscancel;
    public $spoilage_note;
    public $from_date;
    public $to_date;
    private $table = "posspoilages";
    private $tableEmp = "posemployees";

    public function createSpoilage() {
        $fields = array(
            'cashier_id'  => $this->cashier_id,
            'spoilage_note'  => $this->spoilage_note,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function getAllSpoilages() {
        $query = "SELECT
            a.id,
            a.spoilage_note,
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

    public function cancelSpoilage() {
        $query = "UPDATE $this->table SET iscancel=:iscancel WHERE id=:id";

        $fields = array(
            'iscancel'  => $this->iscancel,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}