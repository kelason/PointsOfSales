<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $products = new Suppliers();

    $allSuppliers = $products->getAllSuppliers();

    if ($allSuppliers) {
        echo json_encode(
            [
                "data" => $allSuppliers
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Suppliers."
            ]
        );
    }
}
?>