<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $category = new Categories();
    $category->category_name = $data->category_name;
    $category->category_type = $data->category_type;
    $category->category_status = $data->category_status;

    $resultCategory = $category->createCategory();

    if ($resultCategory) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Category added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Category."
            ]
        );
    }
}
?>