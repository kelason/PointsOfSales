<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data as $key => $value) {
        $res[$key] = $value;
    }

    $changeitem = new ChangeItems();
    $changeitem->iscancel = 1;
    $changeitem->id = $res['changeitem_id'];

    $resultChangeItem = $changeitem->cancelChangeItem();

    if ($resultChangeItem) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Change Item Cancelled."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Change Item."
            ]
        );
    }
}
?>
