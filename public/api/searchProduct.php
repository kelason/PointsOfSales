<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $products = new Products();
    $products->product_name = $_GET['product_name'];
    $products->product_status = "active";

    $products->limit = (isset($_GET['limit']) && $_GET['limit'] == 'all') ? 10000 : 7;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $products->start = ($page - 1) * $products->limit;
    $total = $products->searchProductCount();
    
    $totalPages = ceil($total / $products->limit);
    
    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];
    
    $resultCategory = (isset($_GET['page'])) ? $products->searchPaginationProduct() : $products->searchProduct();

    if ($resultCategory) {
        echo json_encode(
            [
                "data" => $resultCategory,
                "pagination" => $pagination
            ]);
    } else {
        echo json_encode(
        [
            "data" => [],
            "pagination" => $pagination,
            "msg" => "Failed fetching Product."
        ]);
    }
}
?>