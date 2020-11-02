<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $purchase = new Purchases();
    $purchase->id = $_GET['purchase_id'];
    $allPurchases = $purchase->getAllPurchasesById();

    if ($allPurchases) {
        echo json_encode(
            [
                "data" => $allPurchases
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Purchase."
            ]
        );
    }
}
?>