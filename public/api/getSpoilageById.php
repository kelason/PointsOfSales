<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $spoilage = new Spoilages();
    $spoilage->id = $_GET['spoilage_id'];

    $allSpoilage = $spoilage->getSpoilageById();

    if ($allSpoilage) {
        echo json_encode(
            [
                $allSpoilage
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching spoilage."
            ]
        );
    }
}
?>