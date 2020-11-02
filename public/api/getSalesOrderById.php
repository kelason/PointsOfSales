<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $sales = new Sales();
    $sales->id = $_GET['sales_id'];
    $allSales = $sales->getSalesOrderById();

    if ($allSales) {
        echo json_encode(
            [
                "data" => $allSales
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