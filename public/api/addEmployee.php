<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors(($_SERVER['REQUEST_METHOD'] == "POST") ? "POST" : "PUT");

if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $employee = new Employees();
    $employee->id = ($data->id != '') ? $data->id : '' ;
    $employee->employee_fn = $data->employee_fn;
    $employee->employee_mn = $data->employee_mn;
    $employee->employee_sn = $data->employee_sn;
    $employee->employee_phone = $data->employee_phone;
    $employee->employee_brgy = $data->employee_brgy;
    $employee->employee_citymun = $data->employee_citymun;
    $employee->employee_prov = $data->employee_prov;

    $resultEmployee = ($_SERVER['REQUEST_METHOD'] == "POST") ? $employee->addEmployee() : $employee->updateEmployee();

    if ($resultEmployee) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Employee added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Employee."
            ]
        );
    }
}
?>