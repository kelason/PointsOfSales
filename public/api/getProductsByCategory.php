<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $firstID = $categories->getAllCategories()[0]['id'];
    
    $products = new Products();
    $products->catid = ($_GET['catid'] == 0) ? $firstID : $_GET['catid'];
    $products->product_status = "active";
    $productsByCategory = $products->getProductsByCategory();


    if ($productsByCategory) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $productsByCategory
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching products."]);
    }
}
?>