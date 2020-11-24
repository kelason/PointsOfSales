<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $supplier = new Suppliers();
    $supplier->supplier_name = $data->supplier_name;
    $supplier->supplier_phone = $data->supplier_phone;
    $supplier->supplier_brgy = $data->supplier_brgy;
    $supplier->supplier_citymun = $data->supplier_citymun;
    $supplier->supplier_prov = $data->supplier_prov;

    $resultSupplier = $supplier->createSupplier();

    if ($resultSupplier) {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Supplier added successfully."
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