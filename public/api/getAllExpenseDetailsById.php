<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $expense = new ExpenseDetails();
    $expense->id = $_GET['expense_id'];
    $allExpenses = $expense->getAllExpenseDetailsById();

    if ($allExpenses) {
        echo json_encode(
            [
                "data" => $allExpenses
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Expense."
            ]
        );
    }
}
?>