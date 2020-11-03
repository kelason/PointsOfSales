<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $spoilage = new Spoilages();
    $spoilage->id = $data->spoilage_id;
    $spoilage->iscancel = 1;

    $resultEx = $spoilage->cancelSpoilage();
    

    if ($resultExDetails) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Spoilage added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Spoilage."
            ]
        );
    }
}
?>