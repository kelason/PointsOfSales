<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("PUT");

if($_SERVER['REQUEST_METHOD'] == "PUT") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $supplier = new Suppliers();
    $supplier->id = $data->id;
    $supplier->supplier_name = $data->supplier_name;
    $supplier->supplier_phone = $data->supplier_phone;
    $supplier->supplier_brgy = $data->supplier_brgy;
    $supplier->supplier_citymun = $data->supplier_citymun;
    $supplier->supplier_prov = $data->supplier_prov;

    $resultSupplier = $supplier->updateSupplier();

    if ($resultSupplier) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Supplier updated successfully."
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Supplier."
            ]
        );
    }
}
?>