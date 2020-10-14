<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();

    $allCategories = $categories->getAllCategories();

    if ($allCategories) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $allCategories
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching categories."]);
    }
}
?>