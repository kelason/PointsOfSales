<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $suppliers = new Suppliers();

    $suppliers->limit = 15;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $suppliers->start = ($page - 1) * $suppliers->limit;
    $total = $suppliers->supplierCount();

    $totalPages = ceil($total / $suppliers->limit);

    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allSuppliers = (isset($_GET['page']) && $_GET['page'] != '') ? $suppliers->paginationSuppliers() : $suppliers->getAllSuppliers();

    if ($allSuppliers) {
        echo json_encode(
            [
                "data" => $allSuppliers,
                "pagination" => $pagination
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Suppliers.",
                "pagination" => $pagination
            ]
        );
    }
}
?>