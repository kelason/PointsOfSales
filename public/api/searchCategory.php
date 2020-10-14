<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_name = $_GET['category_name'];
    
    $resultCategory = $categories->searchCategory();

    if ($resultCategory) {
        http_response_code(200);
        echo json_encode(
            [
                "data" => $resultCategory
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Failed fetching category."]);
    }
}
?>