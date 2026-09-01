<?php

class ChangeItemProducts extends Database
{
    public $id;
    public $changeid;
    public $productid_out;
    public $change_qty;
    public $fields;
    private $table = "poschangeitem_products";
    private $tableProd = "posproducts";

    public function createChangeItemProduct() {
        return $this->createMany($this->table, $this->fields);
    }

    public function getAllChangeItemProductsById() {
        $query = "SELECT 
            a.id, 
            a.change_qty, 
            b.product_name 
        FROM $this->table AS a 
        INNER JOIN $this->tableProd AS b ON a.productid_out=b.id 
        WHERE 
            a.changeid=?";
        $params = [$this->changeid];
        $result = $this->setRows($query, $params);
        return $result;
    }
}
