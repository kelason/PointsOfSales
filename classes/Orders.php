<?php

class Orders extends Database
{
    public $id;
    public $product_id;
    public $product_qty;
    public $total_amount;
    private $table = "posorders";
    private $tableProduct = "posproducts";

    public function insertOrders() {
        $fields = array(
            'product_id'  => $this->product_id,
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount
        );

        return $this->create($this->table, $fields);
    }

    public function updateOrder() {
        $query = "UPDATE $this->table SET product_qty=:product_qty, total_amount=:total_amount WHERE id=:id";

        $fields = array(
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount,
            'id'  => $this->id,
        );

        return $this->update($query, $fields);
    }

    public function getAllOrders() {
        $query = "SELECT `order`.id AS order_id, `order`.product_qty, `order`.total_amount, `order`.product_id, product.id, product.product_name  FROM $this->table AS `order` INNER JOIN $this->tableProduct AS product ON `order`.product_id = product.id ORDER BY `order`.id";
        $result = $this->setRows($query);
        return $result;
    }
}