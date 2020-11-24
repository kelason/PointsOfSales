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
    private $tableBrgy = "posbrgy";
    private $tableCity = "poscitymun";
    private $tableProv = "posprovince";
    private $tableUser = "posusers";

    public function getAllEmployees() {
        $query = "SELECT 
            a.id, 
            a.employee_fn, 
            a.employee_mn, 
            a.employee_sn, 
            a.employee_phone, 
            a.employee_brgy, 
            a.employee_citymun, 
            a.employee_prov, 
            a.employee_status,
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
            FROM $this->table AS a
            INNER JOIN $this->tableBrgy AS b ON a.employee_brgy=b.brgyCode
            INNER JOIN $this->tableCity AS c ON a.employee_citymun=c.citymunCode
            INNER JOIN $this->tableProv AS d ON a.employee_prov=d.provCode
            WHERE a.employee_status=?";

        $params = [$this->employee_status];
        return $this->setRows($query, $params);
    }

    public function getEmployeesById() {
        $query = "SELECT 
            a.id, 
            a.employee_fn, 
            a.employee_mn, 
            a.employee_sn, 
            a.employee_phone, 
            a.employee_brgy, 
            a.employee_citymun, 
            a.employee_prov, 
            a.employee_status,
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
            FROM $this->table AS a
            INNER JOIN $this->tableBrgy AS b ON a.employee_brgy=b.brgyCode
            INNER JOIN $this->tableCity AS c ON a.employee_citymun=c.citymunCode
            INNER JOIN $this->tableProv AS d ON a.employee_prov=d.provCode
            WHERE a.employee_status=?
            AND a.id=?";

        $params = [$this->employee_status, $this->id];
        return $this->setRows($query, $params);
    }

    public function getAllEmployeesWithoutUser() {
        $query = "SELECT 
            a.id, 
            a.employee_fn, 
            a.employee_mn, 
            a.employee_sn 
            FROM $this->table AS a
            LEFT OUTER JOIN $this->tableUser AS b ON a.id=b.employee_id
            WHERE a.id!=?
            AND b.employee_id IS NULL";

        $params = [$this->id];
        return $this->setRows($query, $params);
    }

    public function getEmployeeExcludeById() {
        $query = "SELECT 
            a.id, 
            a.employee_fn, 
            a.employee_mn, 
            a.employee_sn, 
            a.employee_phone, 
            a.employee_brgy, 
            a.employee_citymun, 
            a.employee_prov, 
            a.employee_status,
            b.brgyDesc, 
            c.citymunDesc, 
            d.provDesc
            FROM $this->table AS a
            INNER JOIN $this->tableBrgy AS b ON a.employee_brgy=b.brgyCode
            INNER JOIN $this->tableCity AS c ON a.employee_citymun=c.citymunCode
            INNER JOIN $this->tableProv AS d ON a.employee_prov=d.provCode
            WHERE a.employee_status=?
            AND a.id!=?";

        $params = [$this->employee_status, $this->id];
        return $this->setRows($query, $params);
    }

    public function addEmployee() {
        $fields = array(
            'employee_fn'  => $this->employee_fn,
            'employee_mn'  => $this->employee_mn,
            'employee_sn'  => $this->employee_sn,
            'employee_phone'  => $this->employee_phone,
            'employee_brgy'  => $this->employee_brgy,
            'employee_citymun'  => $this->employee_citymun,
            'employee_prov'  => $this->employee_prov
        );

        return $this->create($this->table, $fields);
    }

    public function updateEmployee() {
        $query = "UPDATE $this->table 
        SET 
            employee_fn=:employee_fn, 
            employee_mn=:employee_mn, 
            employee_sn=:employee_sn, 
            employee_phone=:employee_phone, 
            employee_brgy=:employee_brgy, 
            employee_citymun=:employee_citymun,
            employee_prov=:employee_prov
        WHERE id=:id";

        $fields = array(
            'employee_fn'  => $this->employee_fn,
            'employee_mn'  => $this->employee_mn,
            'employee_sn'  => $this->employee_sn,
            'employee_phone'  => $this->employee_phone,
            'employee_brgy'  => $this->employee_brgy,
            'employee_citymun'  => $this->employee_citymun,
            'employee_prov'  => $this->employee_prov,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}