<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $orders = new OrderProducts();
    $resultOrders = $orders->getAllOrderProducts();

    if ($resultOrders) {
        echo json_encode(
            [
                "data" => $resultOrders
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Orders."
            ]
        );
    }
}
?>