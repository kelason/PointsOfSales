<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("DELETE");

if($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $order = new OrderProducts();
    $order->id = $_GET['id'];

    $resultOrder = $order->deleteOrderProduct();

    if ($resultOrder) {
        echo json_encode(
            [
                "data" => []
            ]
        );
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