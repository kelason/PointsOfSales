<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $customers = new Customers();
    $customers->customer_status = ($_GET['customer_status'] != '') ? $_GET['customer_status'] : '' ;
    $resultCustomer = ($_GET['customer_status'] == '') ? $customers->getAllCustomers() : $customers->getAllCustomersbyStatus();

    if ($resultCustomer) {
        echo json_encode(
            [
                "data" => $resultCustomer
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Customers."
            ]
        );
    }
}
?>