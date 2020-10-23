<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $purchase = new Purchases();
    $purchase->from_date = DATE("Y-m-d H:i:s", strtotime($_GET['from_date']));
    $purchase->to_date = DATE("Y-m-d H:i:s", strtotime($_GET['to_date']));
    $allPurchases = $purchase->getAllPurchaseByDate();

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