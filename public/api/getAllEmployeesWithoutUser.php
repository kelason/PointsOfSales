<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $employees = new Employees();
    $employees->id = $_GET['id'];
    $allEmployees = $employees->getAllEmployeesWithoutUser();

    if ($allEmployees) {
        echo json_encode(
            [
                "data" => $allEmployees
            ]
        );
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