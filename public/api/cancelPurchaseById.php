<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $purchase = new Purchases();
    $purchase->id = $_GET['purchase_id'];
    $purchase->iscancel = 1;

    $resultPurchase = $purchase->cancelPurchase();

    if ($resultPurchase) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Purchase Deleted successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Purchases."
            ]
        );
    }
}
?>