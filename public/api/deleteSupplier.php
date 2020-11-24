<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));

    $supplier = new Suppliers();
    $supplier->id = $data->id;
    $supplier->isdelete = 1;

    $resultSupplier = $supplier->deleteSupplier();

    if ($resultSupplier) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Supplier Deleted successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed Updating Suppliers."
            ]
        );
    }
}
?>