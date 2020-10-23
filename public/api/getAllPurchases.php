<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $purchase = new Purchases();
    $purchase->limit = 15;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $purchase->start = ($page - 1) * $purchase->limit;
    $total = $purchase->purchaseCount();
    
    $totalPages = ceil($total / $purchase->limit);
    
    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allPurchases = (isset($_GET['page'])) ? $purchase->paginationPurchases() : $purchase->getAllPurchases();

    if ($allPurchases) {
        echo json_encode(
            [
                "data" => $allPurchases,
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