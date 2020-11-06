<?php

class Philippines extends Database
{
    private $tblprovince = "posprovince";
    private $tblcity = "poscitymun";
    private $tblbrgy = "posbrgy";

    public function getAllProvinces() {
        $query = "SELECT id, psgcCode, provDesc, regCode, provCode FROM $this->tblprovince ORDER BY provDesc ASC";
        $result = $this->setRows($query);

        return $result;
    }

    public function setCity($provCode) {
        $query = "SELECT id, psgcCode, citymunDesc, regDesc, citymunCode FROM $this->tblcity WHERE provCode=? ORDER BY citymunDesc ASC";
        $params = [$provCode];
        $result = $this->setRows($query,$params);

        return $result;
    }

    public function setBarangay($citymunCode) {
        $query = "SELECT id, brgyCode, brgyDesc, regCode, provCode, citymunCode FROM $this->tblbrgy WHERE citymunCode=? ORDER BY brgyDesc ASC";
        $params = [$citymunCode];
        $result = $this->setRows($query,$params);

        return $result;
    }
}
?>