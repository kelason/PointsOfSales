<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $spoilage = new Spoilages();
    $spoilage->from_date = DATE("Y-m-d H:i:s", strtotime($_GET['from_date']));
    $spoilage->to_date = DATE("Y-m-d H:i:s", strtotime($_GET['to_date']));

    $allSpoilage = $spoilage->getAllSpoilages();

    if ($allSpoilage) {
        echo json_encode(
            [
                "data" => $allSpoilage
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