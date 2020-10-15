<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $products = new Products();
    $products->id = $_GET['id'];
    $product_price = $products->productShow()[0]['selling_price'];

    $order = new Orders();
    $order->product_id = $_GET['id'];
    $order->product_qty = $_GET['qty'];
    $order->total_amount = $_GET['qty'] * $product_price;

    $resultOrder = $order->insertOrders();

    if ($resultOrder) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $resultOrder
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching Orders."]);
    }
}
?>