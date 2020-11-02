<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {
    $expense = new Expenses();
    $expense->id = $_GET['expense_id'];
    $expense->iscancel = 1;

    $resultExpense = $expense->cancelExpense();

    if ($resultExpense) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "expense Deleted successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Expenses."
            ]
        );
    }
}
?>