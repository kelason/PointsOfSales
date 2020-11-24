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
    public $limit;
    public $start;
    private $table = "possuppliers";
    private $tableBrgy = "posbrgy";
    private $tableCity = "poscitymun";
    private $tableProv = "posprovince";

    public function createSupplier() {
        $fields = array(
            'supplier_name'  => $this->supplier_name,
            'supplier_phone'  => $this->supplier_phone,
            'supplier_brgy'  => $this->supplier_brgy,
            'supplier_citymun'  => $this->supplier_citymun,
            'supplier_prov'  => $this->supplier_prov
        );

        return $this->create($this->table, $fields);
    }

    public function updateSupplier() {
        $query = "UPDATE $this->table 
        SET 
            supplier_name=:supplier_name, 
            supplier_phone=:supplier_phone, 
            supplier_prov=:supplier_prov, 
            supplier_citymun=:supplier_citymun, 
            supplier_brgy=:supplier_brgy 
        WHERE id=:id";

        $fields = array(
            'supplier_name'  => $this->supplier_name,
            'supplier_phone'  => $this->supplier_phone,
            'supplier_prov'  => $this->supplier_prov,
            'supplier_citymun'  => $this->supplier_citymun,
            'supplier_brgy'  => $this->supplier_brgy,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function deleteSupplier() {
        $query = "UPDATE $this->table 
        SET isdelete=:isdelete 
        WHERE id=:id";

        $fields = array(
            'isdelete'  => $this->isdelete,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function getAllSuppliers() {
        $query = "SELECT 
            a.id, 
            a.supplier_name, 
            a.supplier_phone, 
            a.supplier_brgy, 
            a.supplier_citymun, 
            a.supplier_prov, 
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
        FROM $this->table AS a 
        INNER JOIN $this->tableBrgy AS b ON a.supplier_brgy=b.brgyCode
        INNER JOIN $this->tableCity AS c ON a.supplier_citymun=c.citymunCode
        INNER JOIN $this->tableProv AS d ON a.supplier_prov=d.provCode
        WHERE a.isdelete=?";
        $params = [0];

        return $this->setRows($query, $params);
    }

    public function paginationSuppliers() {
        $query = "SELECT 
            a.id, 
            a.supplier_name, 
            a.supplier_phone, 
            a.supplier_brgy, 
            a.supplier_citymun, 
            a.supplier_prov, 
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
        FROM $this->table AS a 
        INNER JOIN $this->tableBrgy AS b ON a.supplier_brgy=b.brgyCode 
        INNER JOIN $this->tableCity AS c ON a.supplier_citymun=c.citymunCode 
        INNER JOIN $this->tableProv AS d ON a.supplier_prov=d.provCode 
        WHERE a.isdelete=?
        ORDER BY a.supplier_name 
        LIMIT $this->start, $this->limit
        ";
        $params = [0];

        return $this->setRows($query, $params);
    }

    public function supplierCount() {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->setColumn($query);
        return $result;
    }

    public function searchSuppliers() {
        $query = "SELECT 
            a.id, 
            a.supplier_name, 
            a.supplier_phone, 
            a.supplier_brgy, 
            a.supplier_citymun, 
            a.supplier_prov, 
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
        FROM $this->table AS a 
        INNER JOIN $this->tableBrgy AS b ON a.supplier_brgy=b.brgyCode 
        INNER JOIN $this->tableCity AS c ON a.supplier_citymun=c.citymunCode 
        INNER JOIN $this->tableProv AS d ON a.supplier_prov=d.provCode 
        WHERE a.isdelete=? 
        AND a.supplier_name LIKE ?
        ORDER BY a.supplier_name 
        LIMIT $this->start, $this->limit
        ";
        $params = [0, "%" . $this->supplier_name . "%"];

        return $this->setRows($query, $params);
    }

    public function searchSupplierCount() {
        $query = "SELECT COUNT(*) FROM $this->table WHERE supplier_name LIKE ?";
        $params = ["%" . $this->supplier_name . "%"];
        $result = $this->setColumn($query, $params);
        return $result;
    }
}