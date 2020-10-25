<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $inventories = new Inventories();
    $inventories->limit = 7;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $inventories->start = ($page - 1) * $inventories->limit;
    $total = $inventories->inventoryCount();

    $totalPages = ceil($total / $inventories->limit);

    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allInventories = $inventories->paginationInventories();

    if ($allInventories) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $allInventories,
                "pagination" => $pagination
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching categories."]);
    }
}
?>