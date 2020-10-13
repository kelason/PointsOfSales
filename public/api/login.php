<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    

    $data = json_decode(file_get_contents("php://input", true));

    $user = new Users();
    $user->username = $data->username;
    $user->userpassword = $data->userpassword;

    $password = $user->getPassword();
    $user_id = $user->getUserID();

    if (password_verify($data->userpassword, $password)) {
        http_response_code(200);
        echo json_encode(
            [
                "msg" => "Successful login.",
                "user_id" => $user_id
            ]);
    } else {
        http_response_code(422);
        echo json_encode(["msg" => "Login failed."]);
    }
}
?>