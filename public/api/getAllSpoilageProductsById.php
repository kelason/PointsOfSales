<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $spoilage = new SpoilageProducts();
    $spoilage->spoilage_id = $_GET['spoilage_id'];
    $result = $spoilage->getAllSpoilageProductsById();

    if ($result) {
        echo json_encode(
            [
                "data" => $result
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Product."
            ]);
    }
}
?>