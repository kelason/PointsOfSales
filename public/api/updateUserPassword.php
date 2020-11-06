<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $employee = new Users();
    $employee->userpassword = $data->newpass;
    $employee->id = $data->id;
    $current_password = $employee->getPasswordById();

    if (password_verify($data->oldpass, $current_password)) {
        $resultEmployee = $employee->updatePassword();
    }

    if ($resultEmployee) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Password updated successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed updateing password."
            ]
        );
    }
}
?>