<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $category_id = $categories->getAllCategories()[0]['id'];

    $products = new Products();
    $products->product_name = $_GET['product_name'];
    $products->product_status = "active";
    $products->catid = (is_null($_GET['category_id'])) ? $category_id : $_GET['category_id']; 
    
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