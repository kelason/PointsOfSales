<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $employees = new Employees();
    $employees->employee_status = $_GET['status'];
    $employees->id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';
    $allEmployees = (isset($_GET['id']) && $_GET['id'] == '') ? $employees->getAllEmployees() : $employees->getEmployeeExcludeById();

    if ($allEmployees) {
        echo json_encode(
            [
                "data" => $allEmployees
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Sales."
            ]
        );
    }
}
?>