<?php

class OrderProducts extends Database
{
    public $id;
    public $order_id;
    public $product_id;
    public $user_id;
    public $product_qty;
    public $total_amount;
    private $table = "posorder_products";
    private $tableOrders = "posorders";
    private $tableProduct = "posproducts";

    public function insertOrderProducts() {
        $fields = array(
            'order_id'  => $this->order_id,
            'product_id'  => $this->product_id,
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount
        );

        return $this->create($this->table, $fields);
    }

    public function updateOrderProduct() {
        $query = "UPDATE $this->table SET product_qty=:product_qty, total_amount=:total_amount WHERE id=:id";

        $fields = array(
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount,
            'id'  => $this->id,
        );

        return $this->update($query, $fields);
    }

    public function updateOrderQty() {
        $query = "UPDATE $this->table SET product_qty=:product_qty, total_amount=:total_amount WHERE order_id=:order_id AND product_id=:product_id";

        $fields = array(
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount,
            'order_id'  => $this->order_id,
            'product_id'  => $this->product_id
        );

        return $this->update($query, $fields);
    }

    public function deleteOrderProduct() {
        $query = "DELETE FROM $this->table WHERE id=?";
        $params = array($this->id);

        $this->destroy($query, $params);
        return $this->resetId($this->table);
    }

    public function existQty() {
        $query = "SELECT a.product_qty FROM $this->table AS a INNER JOIN $this->tableOrders AS b ON a.order_id=b.id INNER JOIN $this->tableProduct AS c ON a.product_id=c.id WHERE c.id=? AND a.order_id=?";
        $params = array($this->product_id, $this->order_id);

        return $this->setColumn($query, $params);
    }

    public function getAllOrderProducts() {
        $query = "SELECT a.id, a.order_id, a.product_id, a.product_qty, a.total_amount, c.product_name FROM $this->table AS a INNER JOIN $this->tableOrders AS b ON a.order_id=b.id INNER JOIN $this->tableProduct AS c ON a.product_id=c.id ORDER BY a.id";
        $result = $this->setRows($query);

        return $result;
    }
}