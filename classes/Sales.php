<?php

class Sales extends Database
{
    public $id;
    public $order_id;
    public $payment_typeid;
    public $customer_id;
    public $cashier_id;
    public $sales_comment;
    public $total_amount;
    public $tendered;
    public $change_amount;
    public $discount_amount;
    public $vat_amount;
    public $created_at;
    private $table = "possales";

    public function createSales() {
        $fields = array(
            'order_id'  => $this->order_id,
            'payment_typeid'  => $this->payment_typeid,
            'customer_id'  => $this->customer_id,
            'cashier_id'  => $this->cashier_id,
            'sales_comment'  => $this->sales_comment,
            'total_amount'  => $this->total_amount,
            'tendered'  => $this->tendered,
            'change_amount'  => $this->change_amount,
            'discount_amount'  => $this->discount_amount,
            'vat_amount'  => $this->vat_amount,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }
}