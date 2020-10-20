<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $firstID = $categories->getAllCategories()[0]['id'];
    
    $products = new Products();
    $products->category_id = ($_GET['catid'] == 0) ? $firstID : $_GET['catid'];
    $products->product_status = "active";
    $productsByCategory = $products->getProductsByCategory();


    if ($productsByCategory) {
        echo json_encode(
            [
                "data" => $productsByCategory
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Product."
            ]);
    }
}
?>