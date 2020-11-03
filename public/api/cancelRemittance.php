<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $remit = new Remittances();
    $remit->id = $data->remit_id;
    $remit->iscancel = 1;

    $resultEx = $remit->cancelRemittance();
    
    $sales = new Sales();
    $sales->sales_status = "not remitted";
    $sales->remit_id = $data->remit_id;
    $resultSales = $sales->cancelRemitSales();

    $expense = new Expenses();
    $expense->expense_status = "not remitted";
    $expense->remit_id = $data->remit_id;
    $resultExpense = $expense->cancelRemitExpense();

    if ($resultExDetails) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Remittance added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Remittance."
            ]
        );
    }
}
?>