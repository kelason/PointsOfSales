<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $sales = new Sales();
    $sales->from_date = DATE("Y-m-d H:i:s", strtotime($_GET['frdate']));
    $sales->to_date = DATE("Y-m-d H:i:s", strtotime($_GET['tdate']));
    $sales->customer_id = $_GET['customer_id'];
    $sales->payment_typeid = $_GET['payment_id'];

    $allSales = $sales->getAllSalesInvoice();

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