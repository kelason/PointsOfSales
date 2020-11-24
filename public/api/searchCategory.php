<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_name = $_GET['category_name'];
    $categories->category_status = "active";

    $categories->limit = 7;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $categories->start = ($page - 1) * $categories->limit;
    $total = $categories->searchCategoryCount();
    
    $totalPages = ceil($total / $categories->limit);
    
    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];
    
    $resultCategory = (isset($_GET['page'])) ? $categories->searchPaginationCategory() : $categories->searchCategory();

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
                "msg" => "Failed fetching Category."
            ]);
    }
}
?>