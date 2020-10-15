<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $orders = new Orders();
    $resultOrders = $orders->getAllOrders();

    if ($resultOrders) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $resultOrders
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching Orders."]);
    }
}
?>