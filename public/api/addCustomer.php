<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors(($_SERVER['REQUEST_METHOD'] == "POST") ? "POST" : "PUT");

if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $employee = new Customers();
    $employee->id = ($data->id != '') ? $data->id : '' ;
    $employee->customer_name = $data->customer_name;
    $employee->customer_phone = $data->customer_phone;
    $employee->customer_status = ($data->customer_status == 1) ? "active" : "inactive";

    $resultEmployee = ($_SERVER['REQUEST_METHOD'] == "POST") ? $employee->addCustomer() : $employee->updateCustomer();

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