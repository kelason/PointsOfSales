<?php

class Suppliers extends Database
{
    public $id;
    public $supplier_name;
    public $supplier_phone;
    public $supplier_street;
    public $supplier_brgy;
    public $supplier_citynum;
    public $supplier_prov;
    public $isdelete;
    private $table = "possuppliers";

    public function getAllSuppliers() {
        $query = "SELECT id, supplier_name, supplier_phone, supplier_street, supplier_brgy, supplier_citymun, supplier_prov FROM $this->table WHERE isdelete=?";
        $params = [0];

        return $this->setRows($query, $params);
    }
}