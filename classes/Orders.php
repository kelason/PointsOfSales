<?php

class Orders extends Database
{
    public $id;
    public $user_id;
    public $created_at;
    public $order_status;
    private $table = "posorders";

    public function insertOrders() {
        $fields = array(
            'cashier_id'  => $this->user_id,
            'created_at'  => $this->created_at
        );

        return $this->create($this->table, $fields);
    }

    public function orderStatus() {
        $query = "SELECT order_status FROM $this->table ORDER BY id DESC LIMIT 1";

        return $this->setColumn($query);
    }

    public function maxOrderId() {
        $query = "SELECT MAX(id) FROM $this->table";

        return $this->setColumn($query);
    }

    public function updateOrderStatusById() {
        $query = "UPDATE $this->table 
        SET order_status=:order_status 
        WHERE id=:id";

        $fields = array(
            'order_status'  => $this->order_status,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }
}