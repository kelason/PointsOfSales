<?php

class Employees extends Database
{
    public $id;
    public $employee_fn;
    public $employee_mn;
    public $employee_sn;
    public $employee_phone;
    public $employee_street;
    public $employee_brgy;
    public $employee_citymun;
    public $employee_prov;
    public $employee_status;
    private $table = "posemployees";

    public function getAllEmployees() {
        $query = "SELECT 
            id, 
            employee_fn, 
            employee_mn, 
            employee_sn, 
            employee_phone, 
            employee_street, 
            employee_brgy, 
            employee_citymun, 
            employee_prov, 
            employee_status 
            FROM $this->table 
            WHERE employee_status=?";

        $params = [$this->employee_status];
        return $this->setRows($query, $params);
    }
}