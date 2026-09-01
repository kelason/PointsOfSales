<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    foreach ($data[0] as $key => $value) {
        $res[$key] = $value;
    }

    if (empty($res['changeitem_product'])) {
        echo json_encode(["data" => [], "msg" => "No products added."]);
        exit;
    }

    $changeitem = new ChangeItems();
    $changeitem->cashier_id = $res['changeitem']->cashier_id;
    $changeitem->created_at = DATE('Y-m-d H:i:s', strtotime($res['changeitem']->created_at));
    $changeitem->changeitem_note = $res['changeitem']->changeitem_note;

    $resultChangeItem = $changeitem->createChangeItem();
    
    $changeitemProd = new ChangeItemProducts();
    foreach ($res['changeitem_product'] as $value) {
        $changeitemProd->fields[] = array(
            'changeid' => $resultChangeItem,
            'productid_out' => $value->product_id,
            'change_qty' => $value->change_qty
        );
    }
    $resultChangeItemProd = $changeitemProd->createChangeItemProduct();

    if ($resultChangeItemProd !== false && $resultChangeItem !== false) {
        echo json_encode(
            [
                "data" => [
                    "msg" => "Change Item added successfully."
                ]
            ]);
    } else {
        echo json_encode(
            [
                "data" => [],
                "msg" => "Failed saving Change Item."
            ]
        );
    }
}
?>
