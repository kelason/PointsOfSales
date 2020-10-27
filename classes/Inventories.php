<?php

class Inventories extends Database
{
    public $id;
    public $order_id;
    public $product_id;
    public $product_name;
    public $category_id;
    public $beginning_date;
    public $from_date;
    public $to_date;
    public $user_id;
    public $product_qty;
    public $total_amount;
    public $vat_amount;
    public $discount_amount;
    public $start;
    public $limit;
    private $tableProd = "posproducts";
    private $tableCat = "poscategories";
    private $tableProCat = "posproduct_categories";
    private $tableOrderProd = "posorder_products";
    private $tableOrder = "posorders";
    private $tablePurchaseProd = "pospurchase_products";
    private $tablePurchase = "pospurchases";

    public function paginationInventories() {
        if ($this->category_id != 0) {
            $where = " AND c.id = ? ";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, "%" . $this->product_name . "%", $this->category_id];
        } else {
            $where = "";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, "%" . $this->product_name . "%"];
        }

        $query = "SELECT 
            b.id, 
            b.product_name, 
            b.product_image, 
            b.unit_price, 
            b.selling_price, 
            b.product_status, 
            b.barcode, 
            b.alarmlvl, 
            c.category_name, 
            d.purchase_qty, 
            e.sales_qty, 
            COALESCE(enp.epurchase_qty,0) - COALESCE(ens.esales_qty,0) AS endstock_qty,
            COALESCE(begp.bpurchase_qty,0) - COALESCE(begs.bsales_qty,0) AS begstock_qty
        FROM $this->tableProCat AS a 
        INNER JOIN $this->tableProd AS b ON a.product_id=b.id 
        INNER JOIN $this->tableCat AS c ON a.category_id=c.id 
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS d ON b.id=d.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS epurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS enp ON b.id=enp.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS bpurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begp ON b.id=begp.product_id
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.product_qty) AS sales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS e ON b.id=e.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS esales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ens ON b.id=ens.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS bsales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begs ON b.id=begs.product_id 
        WHERE b.product_name LIKE ?
        $where
        ORDER BY b.product_name
        LIMIT $this->start, $this->limit";

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getAllInventories() {
        $query = "SELECT 
            b.id, 
            b.product_name, 
            b.product_image, 
            b.unit_price, 
            b.selling_price, 
            b.product_status, 
            b.barcode, 
            b.alarmlvl, 
            c.category_name, 
            d.purchase_qty, 
            e.sales_qty, 
            COALESCE(enp.epurchase_qty,0) - COALESCE(ens.esales_qty,0) AS endstock_qty,
            COALESCE(begp.bpurchase_qty,0) - COALESCE(begs.bsales_qty,0) AS begstock_qty
        FROM $this->tableProCat AS a 
        INNER JOIN $this->tableProd AS b ON a.product_id=b.id 
        INNER JOIN $this->tableCat AS c ON a.category_id=c.id 
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS d ON b.id=d.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS epurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS enp ON b.id=enp.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS bpurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begp ON b.id=begp.product_id
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.product_qty) AS sales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS e ON b.id=e.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS esales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ens ON b.id=ens.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS bsales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begs ON b.id=begs.product_id 
        ORDER BY b.product_name";

        $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date];
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function inventoryCount() {
        if ($this->category_id != 0) {
            $where = " AND c.id = ? ";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, "%" . $this->product_name . "%", $this->category_id];
        } else {
            $where = "";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, "%" . $this->product_name . "%"];
        }

        $query = "SELECT COUNT(b.id)
        FROM $this->tableProCat AS a 
        INNER JOIN $this->tableProd AS b ON a.product_id=b.id 
        INNER JOIN $this->tableCat AS c ON a.category_id=c.id 
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS d ON b.id=d.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS epurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS enp ON b.id=enp.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS bpurchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begp ON b.id=begp.product_id
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.product_qty) AS sales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS e ON b.id=e.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS esales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ens ON b.id=ens.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS bsales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begs ON b.id=begs.product_id 
        WHERE b.product_name LIKE ?
        $where";

        $result = $this->setColumn($query, $params);

        return $result;
    }
}