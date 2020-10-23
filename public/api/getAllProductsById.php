<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $products = new Products();
    $products->product_status = "active";
    $products->id = $_GET['product_id'];

    $allProducts = $products->getAllProductsById();

    if ($allProducts) {
        echo json_encode(
            [
                "data" => $allProducts
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Products."
            ]
        );
    }
}
?>