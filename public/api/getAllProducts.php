<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $products = new Products();

    $allProducts = $products->getAllProducts();

    if ($allProducts) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $allProducts
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Login failed."]);
    }
}
?>