<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $orders = new Orders();
    if ($orders->orderStatus() == "paid" || $orders->orderStatus() == "") {
        $orders->user_id = $_GET['user_id'];
        $orders->created_at = DATE('Y-m-d H:i:s');
        $orders->insertOrders();
    }
    $maxOrderID = $orders->maxOrderId();

    $products = new Products();
    $products->id = $_GET['id'];
    $product_price = $products->productShow()[0]['selling_price'];
    $isvatable = $products->productShow()[0]['isvatable'];

    $orderProduct = new OrderProducts();
    $orderProduct->order_id = $maxOrderID;
    $orderProduct->product_id = $_GET['id'];

    $existQty = $orderProduct->existQty();
    $orderQty = $_GET['qty'];

    ($existQty) ? $orderQty = $existQty + 1 : $orderQty;
    $orderProduct->product_qty = $orderQty;
    $orderProduct->discount_amount = 0;
    $orderProduct->vat_amount = ($isvatable == "yes") ? ($orderQty * $product_price) / 1.12 * 0.12 : 0;
    $orderProduct->total_amount = $orderQty * $product_price;

    $resultOrder = ($existQty) ? $orderProduct->updateOrderQty() : $orderProduct->insertOrderProducts();

    if ($resultOrder) {
        echo json_encode(
            [
                "data" => []
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