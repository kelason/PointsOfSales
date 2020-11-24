<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $category = new Categories();
    $category->id = $data->id;
    $category->isdelete = 1;

    $resultcategory = $category->deleteCategory();

    if ($resultcategory) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "category Deleted successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Category."
            ]
        );
    }
}
?>