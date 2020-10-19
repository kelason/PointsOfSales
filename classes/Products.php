<?php

class Products extends Database
{
    public $id;
    public $category_id;
    public $product_name;
    public $product_image;
    public $unit_price;
    public $selling_price;
    public $product_status;
    public $barcode;
    public $alarmlvl;
    public $isdelete;
    public $limit;
    public $start;
    private $table = "posproducts";
    private $tableCat = "poscategories";
    private $tableProCat = "posproduct_categories";

    public function createProduct() {
        $fields = array(
            'product_name'  => $this->product_name,
            'unit_price'  => $this->unit_price,
            'selling_price'  => $this->selling_price,
            'product_status'  => $this->product_status,
            'product_image'  => $this->product_image,
            'barcode'  => $this->barcode,
            'alarmlvl'  => $this->alarmlvl
        );

        return $this->create($this->table, $fields);
    }

    public function productShow() {
        $query = "SELECT id, product_name, product_image, unit_price, selling_price, product_status, barcode, alarmlvl, isdelete FROM $this->table WHERE id=?";
        $params = [$this->id];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function productCount() {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->setColumn($query);
        return $result;
    }

    public function searchProductCount() {
        $query = "SELECT COUNT(*) FROM $this->table WHERE product_name LIKE ?";
        $params = ["%" . $this->product_name . "%"];
        $result = $this->setColumn($query, $params);
        return $result;
    }

    public function getAllProducts() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? ORDER BY b.product_name";
        $params = [$this->product_status, 0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function paginationProducts() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? ORDER BY b.product_name LIMIT $this->start, $this->limit";
        $params = [$this->product_status, 0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function getProductsByCategory() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? AND c.id=? ORDER BY b.product_name";
        $params = [$this->product_status, 0, $this->category_id];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function searchProductByCategory() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? AND c.id=? AND b.product_name LIKE ? ORDER BY b.product_name";
        $params = [$this->product_status, 0, $this->category_id, "%" . $this->product_name . "%"];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function searchProduct() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? AND b.product_name LIKE ? ORDER BY b.product_name LIMIT $this->start, $this->limit";
        $params = [$this->product_status, 0, "%" . $this->product_name . "%"];
        $result = $this->setRows($query, $params);
        return $result;
    }
}