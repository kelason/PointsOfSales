<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $remitance = new Remittances();
    $remitance->from_date = DATE('Y-m-d', strtotime($_GET['fdate']));
    $remitance->to_date = DATE('Y-m-d', strtotime($_GET['tdate']));
    $allRemitance = $remitance->getAllRemittances();

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