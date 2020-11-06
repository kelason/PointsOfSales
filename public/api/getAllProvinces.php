<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $provinces = new Philippines();
    $result = $provinces->getAllProvinces();

    if ($result) {
        echo json_encode(
            [
                "data" => $result
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Province."
            ]);
    }
}
?>