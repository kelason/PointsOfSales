<?php

class OrderProducts extends Database
{
    public $id;
    public $order_id;
    public $product_id;
    public $user_id;
    public $product_qty;
    public $total_amount;
    public $vat_amount;
    public $discount_amount;
    private $table = "posorder_products";
    private $tableOrders = "posorders";
    private $tableProduct = "posproducts";
    private $tablePurchaseProd = "pospurchase_products";
    private $tablePurchase = "pospurchases";

    public function insertOrderProducts() {
        $fields = array(
            'order_id'  => $this->order_id,
            'product_id'  => $this->product_id,
            'product_qty'  => $this->product_qty,
            'discount_amount'  => $this->discount_amount,
            'vat_amount'  => $this->vat_amount,
            'total_amount'  => $this->total_amount
        );

        return $this->create($this->table, $fields);
    }

    public function updateOrderProduct() {
        $query = "UPDATE $this->table 
        SET product_qty=:product_qty, total_amount=:total_amount, vat_amount=:vat_amount 
        WHERE id=:id";

        $fields = array(
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount,
            'vat_amount'  => $this->vat_amount,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function updateOrderQty() {
        $query = "UPDATE $this->table 
        SET product_qty=:product_qty, total_amount=:total_amount, vat_amount=:vat_amount, discount_amount=:discount_amount 
        WHERE order_id=:order_id 
        AND product_id=:product_id";

        $fields = array(
            'product_qty'  => $this->product_qty,
            'total_amount'  => $this->total_amount,
            'vat_amount'  => $this->vat_amount,
            'discount_amount'  => $this->discount_amount,
            'order_id'  => $this->order_id,
            'product_id'  => $this->product_id
        );

        return $this->update($query, $fields);
    }

    public function updateOrderDiscountById() {
        $query = "UPDATE $this->table 
        SET discount_amount=:discount_amount 
        WHERE id=:id";

        $fields = array(
            'discount_amount'  => $this->discount_amount,
            'id'  => $this->id
        );

        return $this->update($query, $fields);
    }

    public function deleteOrderProduct() {
        $query = "DELETE FROM $this->table WHERE id=?";
        $params = array($this->id);

        return $this->destroy($query, $params);
    }

    public function existQty() {
        $query = "SELECT a.product_qty 
        FROM $this->table AS a 
        INNER JOIN $this->tableOrders AS b ON a.order_id=b.id 
        INNER JOIN $this->tableProduct AS c ON a.product_id=c.id 
        WHERE c.id=? 
        AND a.order_id=?";
        $params = array($this->product_id, $this->order_id);

        return $this->setColumn($query, $params);
    }

    public function getAllOrderProducts() {
        $query = "SELECT a.id, a.order_id, a.product_id, a.product_qty, a.total_amount, a.vat_amount, a.discount_amount, c.product_name, COALESCE(d.total_qty,0) AS stock_qty
        FROM $this->table AS a 
        INNER JOIN $this->tableOrders AS b ON a.order_id=b.id 
        INNER JOIN $this->tableProduct AS c ON a.product_id=c.id
        LEFT JOIN (SELECT a.product_id, SUM(a.purchase_qty) AS total_qty FROM $this->tablePurchaseProd AS a INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id WHERE b.iscancel=? GROUP BY product_id) AS d ON a.product_id=d.product_id
        WHERE b.order_status=?
        ORDER BY a.id";

        $params = [0, "not paid"];
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getOrderDiscountById() {
        $query = "SELECT a.id, a.order_id, a.product_id, a.product_qty, a.total_amount, a.vat_amount, a.discount_amount, c.product_name, c.selling_price
        FROM $this->table AS a 
        INNER JOIN $this->tableOrders AS b ON a.order_id=b.id 
        INNER JOIN $this->tableProduct AS c ON a.product_id=c.id
        WHERE a.id=?
        AND b.order_status=?
        ORDER BY a.id";
        $params = [$this->id, "not paid"];
        $result = $this->setRows($query, $params);
        return $result;
    }
}