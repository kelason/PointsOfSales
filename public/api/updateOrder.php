<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $products = new Products();
    $products->id = $data->product_id;
    $product_price = $products->productShow()[0]['selling_price'];

    $order = new Orders();
    $order->id = $data->id;
    $order->product_qty = $data->qty;
    $order->total_amount = $data->qty * $product_price;

    $resultOrder = $order->updateOrder();

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