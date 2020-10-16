<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $products = new Products();
    $products->id = $data->product_id;
    $product_price = $products->productShow()[0]['selling_price'];

    $order = new OrderProducts();
    $order->id = $data->id;
    $order->product_qty = $data->qty;
    $order->total_amount = $data->qty * $product_price;

    $resultOrder = $order->updateOrderProduct();

    if ($resultOrder) {
        echo json_encode(
            [
                "data" => [],
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