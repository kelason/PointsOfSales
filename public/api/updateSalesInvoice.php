<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $sales = new Sales();
    $sales->id = $data->sales_id;

    $allSales = $sales->updateSalesInvoice();

    if ($allSales) {
        echo json_encode(
            [
                "data" => []
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Sales."
            ]
        );
    }
}
?>