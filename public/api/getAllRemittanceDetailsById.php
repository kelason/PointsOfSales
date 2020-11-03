<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $remitance = new RemittanceDetails();
    $remitance->remit_id = $_GET['remit_id'];
    $allRemitance = $remitance->getAllRemittanceDetailsById();

    if ($allRemitance) {
        echo json_encode(
            [
                "data" => $allRemitance
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Remitance."
            ]
        );
    }
}
?>