<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $products = new Products();
    $products->id = $_GET['id'];
    $product_price = $products->productShow()[0]['selling_price'];

    $payment = new PaymentItems();
    $payment->product_id = $_GET['id'];
    $payment->product_qty = $_GET['qty'];
    $payment->total_amount = $_GET['qty'] * $product_price;

    $resultPayment = $payment->insertQueueItems();

    if ($resultPayment) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $resultPayment
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching category."]);
    }
}
?>