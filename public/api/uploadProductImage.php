<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $products = new Products;
    $products->product_image = $_FILES['file']['name'];
    $explode_id = explode(".", $products->product_image);
    $products->id = $explode_id[0];
    $products->tmp_image = $_FILES['file']['tmp_name'];

    $uploaded = $products->uploadProductImage();
    //$uploaded = '';

    if ($uploaded) {
        echo json_encode(
            [
                "data" => [],
                "msg" => $uploaded
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed uploading product image."
            ]
        );
    }
}
?>