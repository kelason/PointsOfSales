<?php

class Categories extends Database
{
    public $id;
    public $category_name;
    public $category_type;
    public $isdelete;
    private $table = "poscategories";

    public function getAllCategories() {
        $query = "SELECT id, category_name, category_type, isdelete FROM $this->table WHERE isdelete=? ORDER BY category_name";
        $params = [0];
        $result = $this->setRows($query, $params);
        return $result;
    }

    public function searchCategory() {
        $query = "SELECT id, category_name, category_type, isdelete FROM $this->table WHERE isdelete=? AND category_name LIKE ? ORDER BY category_name";
        $params = [0, "%" . $this->category_name . "%"];
        $result = $this->setRows($query, $params);
        return $result;
    }
}