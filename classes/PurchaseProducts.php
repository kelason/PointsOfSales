<?php

class PurchaseProducts extends Database
{
    public $id;
    public $purchase_id;
    public $product_id;
    public $purchase_qty;
    public $total_amount;
    public $fields;
    private $table = "pospurchase_products";
    private $tableProduct = "posproducts";

    public function createPurchaseProduct() {
        return $this->createMany($this->table, $this->fields);
    }

    public function getAllPurchaseProductsById() {
        $query = "SELECT a.id, a.purchase_id, a.product_id, a.purchase_qty, a.total_amount, b.product_name FROM $this->table AS a INNER JOIN $this->tableProduct AS b ON a.product_id=b.id WHERE a.purchase_id=?";
        $params = [$this->id];
        $result = $this->setRows($query, $params);
        return $result;
    }
}