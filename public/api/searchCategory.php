<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $categories = new Categories();
    $categories->category_name = $_GET['category_name'];
    
    $resultCategory = $categories->searchCategory();

    if ($resultCategory) {
        echo json_encode(
            [
                "data" => $resultCategory
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Category."
            ]);
    }
}
?>