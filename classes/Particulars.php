<?php

class Particulars extends Database
{
    public $id;
    public $particulars_name;
    private $table = "posparticulars";
    
    public function getAllParticulars() {
        $query = "SELECT id, particulars_name FROM $this->table";

        $result = $this->setRows($query);
        return $result;
    }

    public function getAllParticularsById() {
        $query = "SELECT id, particulars_name FROM $this->table WHERE id=?";
        $params = [$this->id];

        $result = $this->setRows($query, $params);
        return $result;
    }
}