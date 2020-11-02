<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_status = "active";
    $firstID = $categories->getAllCategories()[0]['id'];
    
    $products = new Products();
    $products->category_id = ($_GET['catid'] == 0) ? $firstID : $_GET['catid'];
    $products->product_status = "active";
    $stocks = $products->getProductStocksByCategory();

    if ($stocks) {
        echo json_encode(
            [
                "data" => $stocks
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