<?php

class Inventories extends Database
{
    public $id;
    public $order_id;
    public $product_id;
    public $category_id;
    public $from_date;
    public $to_date;
    public $user_id;
    public $product_qty;
    public $total_amount;
    public $vat_amount;
    public $discount_amount;
    public $start;
    public $limit;
    private $table = "posproducts";
    private $tableCat = "poscategories";
    private $tableProCat = "posproduct_categories";
    private $tableOrderProd = "posorder_products";
    private $tableOrder = "posorders";
    private $tablePurchaseProd = "pospurchase_products";
    private $tablePurchase = "pospurchases";

    public function paginationInventories() {
        $query = "SELECT b.id, b.product_name, b.product_image, b.unit_price, b.selling_price, b.product_status, b.barcode, b.alarmlvl, c.category_name, COALESCE(d.purchase_qty,0) - COALESCE(e.sales_qty,0) AS stock_qty, d.purchase_qty, e.sales_qty
        FROM $this->tableProCat AS a 
        INNER JOIN $this->table AS b ON a.product_id=b.id 
        INNER JOIN $this->tableCat AS c ON a.category_id=c.id 
        LEFT JOIN (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty FROM $this->tablePurchaseProd AS a INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id WHERE b.iscancel=? GROUP BY product_id) AS d ON a.product_id=d.product_id 
        LEFT JOIN (SELECT a.product_id, SUM(a.product_qty) AS sales_qty FROM $this->tableOrderProd AS a INNER JOIN $this->tableOrder AS b ON a.order_id=b.id WHERE b.order_status=? GROUP BY product_id) AS e ON a.product_id=e.product_id 
        ORDER BY b.product_name
        LIMIT $this->start, $this->limit";

        $params = [0, "paid"];
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function inventoryCount() {
        $query = "SELECT COUNT(b.id)
        FROM $this->tableProCat AS a 
        INNER JOIN $this->table AS b ON a.product_id=b.id 
        INNER JOIN $this->tableCat AS c ON a.category_id=c.id 
        LEFT JOIN (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty FROM $this->tablePurchaseProd AS a INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id WHERE b.iscancel=? GROUP BY product_id) AS d ON a.product_id=d.product_id 
        LEFT JOIN (SELECT a.product_id, SUM(a.product_qty) AS sales_qty FROM $this->tableOrderProd AS a INNER JOIN $this->tableOrder AS b ON a.order_id=b.id WHERE b.order_status=? GROUP BY product_id) AS e ON a.product_id=e.product_id 
        ORDER BY b.product_name";

        $params = [0, "paid"];
        $result = $this->setColumn($query, $params);

        return $result;
    }
}