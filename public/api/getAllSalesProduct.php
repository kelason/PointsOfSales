<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $sales = new Sales();
    $sales->from_date = DATE("Y-m-d", strtotime($_GET['frdate']));
    $sales->to_date = DATE("Y-m-d", strtotime($_GET['tdate']));
    $sales->order_status = "paid";
    $allSales = $sales->getAllSalesProduct();

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