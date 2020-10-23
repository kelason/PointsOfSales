<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $category = new Categories();
    $category->id = $data->id;
    $category->category_name = $data->category_name;
    $category->category_type = $data->category_type;
    $category->category_status = (isset($data->category_status) == 1) ? 'active' : 'inactive';

    $resultCategory = $category->updateCategory();

    if ($resultCategory) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Product Updated successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Products."
            ]
        );
    }
}
?>