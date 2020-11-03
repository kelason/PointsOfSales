<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $expense = new Expenses();
    $allExpense = $expense->getAllExpenseAmount();

    if ($allExpense) {
        echo json_encode(
            [
                "data" => $allExpense
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Expenses."
            ]
        );
    }
}
?>