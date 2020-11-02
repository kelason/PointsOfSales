<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $product = new Products();
    $product->id = $data->id;
    $product->isdelete = 1;

    $resultProduct = $product->deleteProduct();

    if ($resultProduct) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Product Deleted successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Products."
            ]
        );
    }
}
?>