<?php

class Products extends Database
{
    public $id;
    public $catid;
    public $product_name;
    public $unit_price;
    public $selling_price;
    public $product_status;
    public $barcode;
    public $alarmlvl;
    public $isdelete;
    private $table = "posproducts";

    public function getAllProducts() {
        $query = "SELECT id, catid, product_name, product_image, unit_price, selling_price, product_status, barcode, alarmlvl, isdelete FROM $this->table WHERE product_status=? AND isdelete=? ORDER BY product_name";
        $params = ["active", 0];
        $result = $this->setRows($query, $params);
        return $result;
    }
}