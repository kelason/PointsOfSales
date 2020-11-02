<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_status = "active";
    $category_id = $categories->getAllCategories()[0]['id'];

    $products = new Products();
    $products->product_name = $_GET['product_name'];
    $products->product_status = "active";
    $products->category_id = ($_GET['category_id'] == 'null') ? $category_id : $_GET['category_id']; 
    
    $resultProduct = $products->searchProductByCategory();

    if ($resultProduct) {
        echo json_encode(
            [
                "data" => $resultProduct
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