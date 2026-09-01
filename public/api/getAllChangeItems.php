<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $changeitem = new ChangeItems();
    $changeitem->from_date = DATE("Y-m-d H:i:s", strtotime($_GET['from_date']));
    $changeitem->to_date = DATE("Y-m-d H:i:s", strtotime($_GET['to_date']));

    $allChangeItems = $changeitem->getAllChangeItems();

    if ($allChangeItems) {
        echo json_encode(
            [
                "data" => $allChangeItems
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Change Items."
            ]);
    }
}
?>
