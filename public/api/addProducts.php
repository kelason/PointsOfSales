<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';
//load library
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/vendor/autoload.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $barcode = genRandStr();

    $product = new Products();
    $product->product_name = $data->product_name;
    $product->product_image = "no-thumbnail.jpg";
    $product->category_id = $data->category_id;
    $product->unit_price = $data->unit_price;
    $product->selling_price = $data->selling_price;
    $product->product_status = (isset($data->product_status) == 1) ? 'active' : 'inactive';
    $product->barcode = $barcode;
    $product->alarmlvl = (isset($data->alarmlvl) == '') ? 0 : $data->alarmlvl;

    $resultProduct = $product->createProduct();

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/grocery/public/images/barcode/$resultProduct.png", $generator->getBarcode($barcode, $generator::TYPE_CODE_128));

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