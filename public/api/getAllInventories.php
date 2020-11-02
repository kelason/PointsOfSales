<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $inventories = new Inventories();
    $inventories->limit = 15;
    $inventories->product_name = (isset($_GET['product_name'])) ? $_GET['product_name'] : '' ;
    $inventories->category_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : '' ;
    $inventories->beginning_date = DATE('Y-m-d', strtotime("-1 day",strtotime($_GET['fdate'])));
    $inventories->from_date = DATE('Y-m-d', strtotime($_GET['fdate']));
    $inventories->to_date = DATE('Y-m-d', strtotime($_GET['tdate']));
    
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

    $allInventories = (isset($_GET['page']) && $_GET['page'] != '') ? $inventories->paginationInventories() : $inventories->getAllInventories();

    if ($allInventories) {
        echo json_encode(
            [
                "data" => $allInventories,
                "pagination" => $pagination
            ]);
    } else {
        echo json_encode(["msg" => "Failed fetching Inventories."]);
    }
}
?>