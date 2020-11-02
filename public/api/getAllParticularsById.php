<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $particulars = new Particulars();
    $particulars->id = $_GET['particulars_id'];
    $allParticulars = $particulars->getAllParticularsById();

    if ($allParticulars) {
        echo json_encode(
            [
                "data" => $allParticulars
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Particulars."
            ]
        );
    }
}
?>