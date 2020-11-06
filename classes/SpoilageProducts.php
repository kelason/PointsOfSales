<?php

class SpoilageProducts extends Database
{
    public $id;
    public $spoilage_id;
    public $product_id;
    public $spoilage_qty;
    public $total_amount;
    public $fields;
    private $table = "posspoilage_products";
    private $tableProd = "posproducts";

    public function createSpoilageProduct() {
        return $this->createMany($this->table, $this->fields);
    }

    public function getAllSpoilageProductsById() {
        $query = "SELECT 
            a.id, 
            a.spoilage_qty, 
            b.product_name 
        FROM $this->table AS a 
        INNER JOIN $this->tableProd AS b ON a.product_id=b.id 
        WHERE 
            a.spoilage_id=?";
        $params = [$this->spoilage_id];
        $result = $this->setRows($query, $params);
        return $result;
    }
}