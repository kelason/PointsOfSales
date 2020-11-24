<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $product = new Products();
    $product->product_name = $data->product_name;
    $product->product_image = "no-thumbnail.jpg";
    $product->category_id = $data->category_id;
    $product->unit_price = $data->unit_price;
    $product->selling_price = $data->selling_price;
    $product->product_status = (isset($data->product_status) == 1) ? 'active' : 'inactive';
    $product->barcode = (isset($data->barcode) == '') ? '' : $data->barcode;
    $product->alarmlvl = (isset($data->alarmlvl) == '') ? 0 : $data->alarmlvl;

    $resultProduct = $product->createProduct();

    $productCategories = new ProductCategories();
    $productCategories->product_id = $resultProduct;
    $productCategories->category_id = $data->category_id;

    $resultProductCategory = $productCategories->createProductCategories();

    if ($resultProduct) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Product added successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Adding Products."
            ]
        );
    }
}
?>