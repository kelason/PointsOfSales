<?php

class PaymentItems extends Database
{
    public $id;
    public $payment_id;
    public $product_id;
    public $product_qty;
    public $total_amount;
    //private $payment_item_field = "id, payment_id, product_id, product_qty, total_amount";
    private $table = "pospayment_items";

    public function insertQueueItems() {
        $fields = array(
            'product_id'  => $this->product_id,
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount
        );

        return $this->create($this->table, $fields);
    }
}