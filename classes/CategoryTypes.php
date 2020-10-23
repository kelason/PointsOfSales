<?php

class CategoryTypes extends Database
{
    public $id;
    public $type_name;
    private $table = "poscategory_types";

    public function getAllCategoryTypes() {
        $query = "SELECT id, `type_name` FROM $this->table ORDER BY `type_name`";
        $result = $this->setRows($query);
        return $result;
    }
}