<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data[0] as $key => $value) {
        $res[$key] = $value;
    }

    $spoilage = new Spoilages();
    $spoilage->cashier_id = $res['spoilage']->cashier_id;
    $spoilage->created_at = DATE('Y-m-d H:i:s', strtotime($res['spoilage']->created_at));
    $spoilage->spoilage_note = $res['spoilage']->spoilage_note;

    $resultSpoil = $spoilage->createSpoilage();
    
    $spoilage = new SpoilageProducts();
    foreach ($res['spoilage_product'] as $value) {
        $spoilage->fields[] = array(
            'spoilage_id' => $resultSpoil,
            'product_id' => $value->product_id,
            'spoilage_qty' => $value->spoilage_qty
        );
    }
    $resultSpoilProd = $spoilage->createSpoilageProduct();

    if ($resultSpoilProd && $resultSpoil) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Spoilage added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed fetching Spoilage."
            ]
        );
    }
}
?>