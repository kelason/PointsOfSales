<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->id = $_GET['id'];
    $allCategory = $categories->getCategoryName();

    if ($allCategory) {
        echo json_encode(
            [
                "data" => $allCategory
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Categories."
            ]
        );
    }
}
?>