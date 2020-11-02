<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    

    $data = json_decode(file_get_contents("php://input", true));

    $user = new Users();
    $user->username = $data->username;
    $user->userpassword = $data->userpassword;
    $user->user_status = "active";
    $user->user_approval = $user->getUserApproval();

    $password = $user->getPassword();
    $user_id = $user->getUserID();

    if (password_verify($data->userpassword, $password)) {
        echo json_encode(
            [
                "msg" => "Successful login.",
                "user_approval" => $user->user_approval,
                "user_id" => $user_id
            ]);
    } else {
        echo json_encode(
            [
                "msg" => "Wrong Username or Password",
                "user_id" => 0
            ]);
    }
}
?>