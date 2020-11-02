<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data[0] as $key => $value) {
        $res[$key] = $value;
    }

    $expense = new Expenses();
    $expense->total_amount = $res['expense']->total_amount;
    $expense->cashier_id = $res['expense']->cashier_id;
    $expense->created_at = DATE('Y-m-d H:i:s', strtotime($res['expense']->created_at));
    $expense->expense_note = $res['expense']->expense_note;

    $resultEx = $expense->createExpense();
    
    $purchase = new ExpenseDetails();
    foreach ($res['expense_details'] as $value) {
        $purchase->fields[] = array(
            'expense_id' => $resultEx,
            'particulars_id' => $value->particulars_id,
            'expense_amount' => $value->expense_amount
        );
    }
    $resultExDetails = $purchase->createExpenseDetails();

    if ($resultExDetails && $resultEx) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Purchase added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Purchase."
            ]
        );
    }
}
?>