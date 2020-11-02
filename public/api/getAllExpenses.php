<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $expense = new Expenses();
    $expense->from_date = DATE("Y-m-d", strtotime($_GET['from_date']));
    $expense->to_date = DATE("Y-m-d", strtotime($_GET['to_date']));
    $allExpenses = $expense->getAllExpenses();

    if ($allExpenses) {
        echo json_encode(
            [
                "data" => $allExpenses
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