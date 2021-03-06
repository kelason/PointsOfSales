<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $products = new Products();
    $products->product_status = "active";
    $products->limit = 7;

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

    $allProducts = (isset($_GET['page']) && $_GET['page'] != '') ? $products->paginationProducts() : $products->getAllProducts();

    if ($allProducts) {
        echo json_encode(
            [
                "data" => $allProducts,
                "pagination" => $pagination
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "pagination" => $pagination,
                "msg" => "Failed fetching Products."
            ]
        );
    }
}
?>