<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $user = new Users();
    $user->employee_id = $data->employee_id;
    $user->username = $data->username;
    $user->user_status = ($data->user_status == 1) ? "active" : "inactive";
    $user->user_approval = ($data->user_approval == 1) ? "cashier" : "manager";
    $user->created_at = Date('Y-m-d H:i:s');

    $resultUser = $user->createUser();

    if ($resultUser) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "user added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Employee."
            ]
        );
    }
}
?>