<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $suppliers = new Suppliers();
    $suppliers->supplier_name = $_GET['supplier_name'];
    $suppliers->limit = 15;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $suppliers->start = ($page - 1) * $suppliers->limit;
    $total = $suppliers->searchSupplierCount();

    $totalPages = ceil($total / $suppliers->limit);

    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allSupplier = $suppliers->searchSuppliers();

    if ($allSupplier) {
        echo json_encode(
            [
                "data" => $allSupplier,
                "pagination" => $pagination
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "pagination" => $pagination,
                "msg" => "Failed fetching supplier."
            ]
        );
    }
}
?>