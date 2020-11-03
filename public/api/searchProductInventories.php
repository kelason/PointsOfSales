<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $spoilage = new Inventories();
    $spoilage->product_name = $_GET['product_name'];
    $allSpoilage = $spoilage->searchProductInventories();

    if ($allSpoilage) {
        echo json_encode(
            [
                "data" => $allSpoilage
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Spoilage."
            ]
        );
    }
}
?>