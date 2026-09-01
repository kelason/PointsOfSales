<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $changeitem = new ChangeItems();
    $changeitem->id = $_GET['changeitem_id'];

    $resultChangeItem = $changeitem->getChangeItemById();

    if ($resultChangeItem) {
        echo json_encode(
            [
                "data" => $resultChangeItem
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Change Item."
            ]);
    }
}
?>
