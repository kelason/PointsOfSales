<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $payment_types = new PaymentTypes();
    $resultPayment = $payment_types->getAllPaymentTypes();

    if ($resultPayment) {
        echo json_encode(
            [
                "data" => $resultPayment
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Payment Types."
            ]
        );
    }
}
?>