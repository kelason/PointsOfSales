<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $category_types = new CategoryTypes();
    $firstID = $category_types->getAllCategoryTypes()[0]['id'];

    $categories = new Categories();
    $categories->category_type = ($_GET['typeid'] == 0) ? $firstID : $_GET['typeid'];
    $categories->category_status = $_GET['category_status'];
    
    $resultCategory = $categories->getAllCategoriesByTypeId();

    if ($resultCategory) {
        echo json_encode(
            [
                "data" => $resultCategory
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Category Types."
            ]);
    }
}
?>