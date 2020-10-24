<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $orderProduct = new OrderProducts();
    $orderProduct->id = $_GET['order_id'];

    $resultOrder = $orderProduct->getOrderDiscountById();

    if ($resultOrder) {
        echo json_encode(
            [
                "data" => $resultOrder
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