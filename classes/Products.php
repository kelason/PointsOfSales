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
    private $product_fields = "id, catid, product_name, product_image, unit_price, selling_price, product_status, barcode, alarmlvl, isdelete";
    private $table = "posproducts";

    public function productShow() {
        $query = "SELECT $this->product_fields FROM $this->table WHERE id=?";
        $params = [$this->id];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function getAllProducts() {
        $query = "SELECT $this->product_fields FROM $this->table WHERE product_status=? AND isdelete=? ORDER BY product_name";
        $params = [$this->product_status, 0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function getProductsByCategory() {
        $query = "SELECT $this->product_fields FROM $this->table WHERE product_status=? AND isdelete=? AND catid=? ORDER BY product_name";
        $params = [$this->product_status, 0, $this->catid];
        $result = $this->setRows($query, $params);
        return $result;
    }
}