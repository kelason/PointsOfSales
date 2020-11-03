<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data[0] as $key => $value) {
        $res[$key] = $value;
    }

    $remit = new Remittances();
    $remit->expense_amount = $res['remittances']->expense_amount;
    $remit->sales_amount = $res['remittances']->sales_amount;
    $remit->remitted_amount = $res['remittances']->remitted_amount;
    $remit->cashier_id = $res['remittances']->cashier_id;
    $remit->created_at = DATE('Y-m-d H:i:s');

    $resultEx = $remit->createRemittance();
    
    $remit_details = new RemittanceDetails();
    foreach ($res['remittance_details'] as $value) {
        $remit_details->fields[] = array(
            'remit_id' => $resultEx,
            'remit_qty' => $value->remit_qty,
            'remit_amount' => $value->remit_amount,
            'remit_total' => $value->remit_total
        );
    }
    $resultExDetails = $remit_details->createExpenseDetails();

    $sales = new Sales();
    $sales->sales_status = "remitted";
    $sales->remit_id = $resultEx;
    $resultSales = $sales->remitSales();

    $expense = new Expenses();
    $expense->expense_status = "remitted";
    $expense->remit_id = $resultEx;
    $resultExpense = $expense->remitExpense();

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