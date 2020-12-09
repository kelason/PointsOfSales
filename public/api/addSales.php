<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));

    $sales = new Sales();
    $sales->payment_typeid = $data[0]->payment_typeid;
    $sales->customer_id = $data[0]->customer_id;
    $sales->cashier_id = $data[0]->cashier_id;
    $sales->order_id = $data[0]->order_id;
    $sales->sales_comment = $data[0]->sales_comment;
    $sales->total_amount = $data[0]->total_amount;
    $sales->tendered = $data[0]->tendered;
    $sales->sales_code = genRandStr();
    $sales->change_amount = $data[0]->change_amount;
    $sales->discount_amount = $data[0]->discount_amount;
    $sales->vat_amount = $data[0]->vat_amount;
    $sales->created_at = DATE('Y-m-d H:i:s');

    $resultSales = $sales->createSales();
    
    $orders = new Orders();
    $orders->id = $data[0]->order_id;
    $orders->order_status = "paid";
    $orders->created_at = DATE('Y-m-d H:i:s');
    $resultOrders = $orders->updateOrderStatusById();

    if($resultSales && $resultOrders) 

    if ($resultSales) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Sales added successfully."
                ]
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