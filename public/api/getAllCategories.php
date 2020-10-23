<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_status = "active";
    $categories->limit = 15;

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $categories->start = ($page - 1) * $categories->limit;
    $total = $categories->categoryCount();

    $totalPages = ceil($total / $categories->limit);

    $pagination = [
        "current_page" => $page,
        "last_page" => $totalPages
    ];

    $allCategories = (isset($_GET['page'])) ? $categories->paginationCategories() : $categories->getAllCategories();

    if ($allCategories) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $allCategories,
                "pagination" => $pagination
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching categories."]);
    }
}
?>