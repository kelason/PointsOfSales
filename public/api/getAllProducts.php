<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

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
    $total = $products->productCount();
    
    $totalPages = ceil($total / $products->limit);
    
    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allProducts = $products->paginationProducts();

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
                "msg" => "Failed fetching Products."
            ]
        );
    }
}
?>