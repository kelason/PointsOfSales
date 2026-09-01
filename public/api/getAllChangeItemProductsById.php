<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $changeitem = new ChangeItemProducts();
    $changeitem->changeid = $_GET['changeitem_id'];

    $resultChangeItem = $changeitem->getAllChangeItemProductsById();

    if ($resultChangeItem) {
        echo json_encode(
            [
                "data" => $resultChangeItem
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Change Item Products."
            ]);
    }
}
?>
