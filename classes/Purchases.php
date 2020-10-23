<?php

class Purchases extends Database
{
    public $id;
    public $supplier_id;
    public $cashier_id;
    public $drnum;
    public $purchase_note;
    public $iscancel;
    public $created_at;
    public $limit;
    public $start;
    public $from_date;
    public $to_date;
    private $table = "pospurchases";
    private $tableSupplier = "possuppliers";
    private $tableEmployee = "posemployees";

    public function createPurchase() {
        $fields = array(
            'supplier_id'  => $this->supplier_id,
            'cashier_id'  => $this->cashier_id,
            'drnum'  => $this->drnum,
            'purchase_note'  => $this->purchase_note,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function purchaseCount() {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->setColumn($query);
        return $result;
    }

    public function getAllPurchases() {
        $query = "SELECT a.id, a.supplier_id, a.cashier_id, a.drnum, a.purchase_note, a.created_at, a.iscancel, b.supplier_name, c.employee_fn, c.employee_sn FROM $this->table AS a INNER JOIN $this->tableSupplier AS b ON a.supplier_id=b.id INNER JOIN $this->tableEmployee AS c ON a.cashier_id=c.id";
        $result = $this->setRows($query);
        return $result;
    }

    public function getAllPurchasesById() {
        $query = "SELECT a.id, a.supplier_id, a.cashier_id, a.drnum, a.purchase_note, a.created_at, a.iscancel, b.supplier_name, c.employee_fn, c.employee_sn FROM $this->table AS a INNER JOIN $this->tableSupplier AS b ON a.supplier_id=b.id INNER JOIN $this->tableEmployee AS c ON a.cashier_id=c.id WHERE a.id=?";
        $params = [$this->id];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function getAllPurchaseByDate() {
        $query = "SELECT a.id, a.supplier_id, a.cashier_id, a.drnum, a.purchase_note, a.created_at, a.iscancel, b.supplier_name, c.employee_fn, c.employee_sn FROM $this->table AS a INNER JOIN $this->tableSupplier AS b ON a.supplier_id=b.id INNER JOIN $this->tableEmployee AS c ON a.cashier_id=c.id WHERE a.created_at BETWEEN ? AND ?";
        $params = [$this->from_date, $this->to_date];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function paginationPurchases() {
        $query = "SELECT a.id, a.supplier_id, a.cashier_id, a.drnum, a.purchase_note, a.created_at, a.iscancel, b.supplier_name, c.employee_fn, c.employee_sn FROM $this->table AS a INNER JOIN $this->tableSupplier AS b ON a.supplier_id=b.id INNER JOIN $this->tableEmployee AS c ON a.cashier_id=c.id LIMIT $this->start, $this->limit";
        $result = $this->setRows($query);
        return $result;
    }

    public function cancelPurchase() {
        $query = "UPDATE $this->table SET iscancel=:iscancel WHERE id=:id";

        $fields = array(
            'iscancel'  => $this->iscancel,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}