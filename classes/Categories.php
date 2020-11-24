<?php

class Categories extends Database
{
    public $id;
    public $category_name;
    public $category_type;
    public $isdelete;
    public $category_status;
    public $limit;
    public $start;
    private $table = "poscategories";
    private $tableType = "poscategory_types";

    public function createCategory() {
        $fields = array(
            'category_name'  => $this->category_name,
            'category_type'  => $this->category_type,
            'category_status'  => $this->category_status
        );

        return $this->create($this->table, $fields);
    }

    public function updateCategory() {
        $query = "UPDATE $this->table SET category_name=:category_name, category_type=:category_type, category_status=:category_status WHERE id=:id";

        $fields = array(
            'category_name'  => $this->category_name,
            'category_type'  => $this->category_type,
            'category_status'  => $this->category_status,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function deleteCategory() {
        $query = "UPDATE $this->table 
        SET isdelete=:isdelete 
        WHERE id=:id";

        $fields = array(
            'isdelete'  => $this->isdelete,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function getAllCategories() {
        $query = "SELECT id, category_name, category_type, isdelete FROM $this->table WHERE category_status=? AND isdelete=? ORDER BY category_name";
        $params = [$this->category_status, 0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function getCategoryName() {
        $query = "SELECT category_name FROM $this->table WHERE id=?";
        $params = [$this->id];
        $result = $this->setColumn($query, $params);
        return $result;
    }

    public function categoryCount() {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->setColumn($query);
        return $result;
    }

    public function searchCategoryCount() {
        $query = "SELECT COUNT(*) FROM $this->table WHERE category_name LIKE ?";
        $params = ["%" . $this->category_name . "%"];
        $result = $this->setColumn($query, $params);
        return $result;
    }

    public function paginationCategories() {
        $query = "SELECT a.id, a.category_name, a.category_type, a.category_status, b.type_name FROM $this->table AS a INNER JOIN $this->tableType AS b ON a.category_type=b.id WHERE a.isdelete=? ORDER BY a.category_name LIMIT $this->start, $this->limit";
        $params = [0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function searchCategory() {
        $query = "SELECT a.id, a.category_name, a.category_type, a.category_status, b.type_name FROM $this->table AS a INNER JOIN $this->tableType AS b ON a.category_type=b.id WHERE a.category_status=? AND a.isdelete=? AND a.category_name LIKE ? ORDER BY a.category_name";
        $params = [$this->category_status, 0, "%" . $this->category_name . "%"];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function searchPaginationCategory() {
        $query = "SELECT a.id, a.category_name, a.category_type, a.category_status, b.type_name FROM $this->table AS a INNER JOIN $this->tableType AS b ON a.category_type=b.id WHERE a.isdelete=? AND category_name LIKE ? ORDER BY category_name";
        $params = [0, "%" . $this->category_name . "%"];
        $result = $this->setRows($query, $params);
        return $result;
    }
}