<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $city = new Philippines();
    $result = $city->setCity($_GET['provCode']);

    if ($result) {
        echo json_encode(
            [
                "data" => $result
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching City."
            ]);
    }
}
?>