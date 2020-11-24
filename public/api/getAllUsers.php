<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("GET");

if($_SERVER['REQUEST_METHOD'] == "GET") {

    $users = new Users();

    $allUser = $users->getAllUsers();

    if ($allUser) {
        echo json_encode(
            [
                "data" => $allUser
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Suppliers."
            ]
        );
    }
}
?>