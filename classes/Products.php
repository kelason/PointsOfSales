<?php

class Products extends Database
{
    public $id;
    public $category_id;
    public $product_name;
    public $product_image;
    public $tmp_image;
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

    public function updateProduct() {
        $query = "UPDATE $this->table SET product_name=:product_name, unit_price=:unit_price, selling_price=:selling_price, product_status=:product_status, barcode=:barcode, alarmlvl=:alarmlvl WHERE id=:id";

        $fields = array(
            'product_name'  => $this->product_name,
            'unit_price'  => $this->unit_price,
            'selling_price'  => $this->selling_price,
            'product_status'  => $this->product_status,
            'barcode'  => $this->barcode,
            'alarmlvl'  => $this->alarmlvl,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function deleteProduct() {
        $query = "UPDATE $this->table SET isdelete=:isdelete WHERE id=:id";

        $fields = array(
            'isdelete'  => $this->isdelete,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function uploadProductImage() {
        $valid_extensions = array("jpg","jpeg","png");
        $extension = pathinfo($this->product_image, PATHINFO_EXTENSION);

        if (in_array($extension, $valid_extensions)) {
            $upload_path = WWW_ROOT . '/public/images/products/' . $this->product_image;
            if (move_uploaded_file($this->tmp_image, $upload_path)) {
                $query = "UPDATE $this->table SET product_image=:product_image WHERE id=:id";

                $fields = array(
                    'product_image'  => $this->product_image,
                    'id'  => $this->id
                );

                $result = $this->update($query, $fields);
                return $result;
            }
        }
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
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name, c.id AS category_id FROM $this->tableProCat AS a INNER JOIN $this->table AS b ON a.product_id=b.id INNER JOIN $this->tableCat AS c ON a.category_id=c.id WHERE b.product_status=? AND b.isdelete=? ORDER BY b.product_name LIMIT $this->start, $this->limit";
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