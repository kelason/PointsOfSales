<?php
require $_SERVER['DOCUMENT_ROOT'] . 'grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data[0] as $key => $value) {
        $res[$key] = $value;
    }

    $purchase = new Purchases();
    $purchase->supplier_id = $res['purchase']->supplier_id;
    $purchase->drnum = $res['purchase']->drnum;
    $purchase->cashier_id = $res['purchase']->cashier_id;
    $purchase->created_at = DATE('Y-m-d H:i:s', strtotime($res['purchase']->created_at));
    $purchase->purchase_note = $res['purchase']->purchase_note;

    $resultPur = $purchase->createPurchase();
    
    $purchase = new PurchaseProducts();
    foreach ($res['purchase_product'] as $value) {
        $purchase->fields[] = array(
            'purchase_id' => $resultPur,
            'product_id' => $value->product_id,
            'purchase_qty' => $value->purchase_qty,
            'total_amount' => ($value->unit_price * $value->purchase_qty)
        );
    }
    $resultPurProd = $purchase->createPurchaseProduct();

    if ($resultPurProd && $resultPur) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Purchase added successfully."
                ]
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