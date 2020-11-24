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
    private $tableSpoilageProd = "posspoilage_products";
    private $tableSpoilage = "posspoilages";

    public function paginationInventories() {
        if ($this->category_id != 0) {
            $where = " AND c.id = ? ";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "%" . $this->product_name . "%", 0, $this->category_id];
        } else {
            $where = "";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "%" . $this->product_name . "%", 0];
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
            f.spoilage_qty, 
            COALESCE(enp.epurchase_qty,0) - COALESCE(ens.esales_qty,0) - COALESCE(ensp.sespoilage_qty,0) AS endstock_qty,
            COALESCE(begp.bpurchase_qty,0) - COALESCE(begs.bsales_qty,0) - COALESCE(begsp.sbspoilage_qty,0) AS begstock_qty
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
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.spoilage_qty) AS spoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS f ON b.id=f.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sespoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ensp ON b.id=ensp.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sbspoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id  
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begsp ON b.id=begsp.product_id 
        WHERE b.product_name LIKE ?
        AND b.isdelete=?
        $where
        ORDER BY b.product_name
        LIMIT $this->start, $this->limit";

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getAllInventories() {
        if ($this->category_id != 0) {
            $where = " AND c.id = ? ";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "%" . $this->product_name . "%", 0, $this->category_id];
        } else {
            $where = "";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "%" . $this->product_name . "%", 0];
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
            f.spoilage_qty, 
            COALESCE(enp.epurchase_qty,0) - COALESCE(ens.esales_qty,0) - COALESCE(ensp.sespoilage_qty,0) AS endstock_qty,
            COALESCE(begp.bpurchase_qty,0) - COALESCE(begs.bsales_qty,0) - COALESCE(begsp.sbspoilage_qty,0) AS begstock_qty
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
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.spoilage_qty) AS spoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS f ON b.id=f.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sespoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ensp ON b.id=ensp.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sbspoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id  
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begsp ON b.id=begsp.product_id 
        WHERE b.product_name LIKE ?
        AND b.isdelete=? 
        $where 
        ORDER BY b.product_name";
            
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function searchProductInventories() {
        $query = "SELECT 
            a.id, 
            a.product_name, 
            a.unit_price, 
            b.purchase_qty, 
            c.sales_qty, 
            d.spoilage_qty,
            COALESCE(b.purchase_qty,0) - COALESCE(c.sales_qty,0) - COALESCE(d.spoilage_qty,0) AS stock_qty
        FROM $this->tableProd AS a 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            GROUP BY a.product_id)
        AS b ON a.id=b.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS sales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            GROUP BY a.product_id)
        AS c ON a.id=c.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS spoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id 
            WHERE b.iscancel=?
            GROUP BY a.product_id)
        AS d ON a.id=d.product_id 
        WHERE a.product_name LIKE ?
        AND a.isdelete=?
        ORDER BY a.product_name";

        $params = [0, "paid", 0, "%" . $this->product_name . "%", 0];
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getProductInventoriesById() {
        $query = "SELECT 
            a.id, 
            a.product_name, 
            COALESCE(b.purchase_qty,0) - COALESCE(c.sales_qty,0) - COALESCE(d.spoilage_qty,0) AS stock_qty
        FROM $this->tableProd AS a 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.purchase_qty) AS purchase_qty 
            FROM $this->tablePurchaseProd AS a 
            INNER JOIN $this->tablePurchase AS b ON a.purchase_id=b.id  
            WHERE b.iscancel=?
            GROUP BY a.product_id)
        AS b ON a.id=b.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.product_qty) AS sales_qty 
            FROM $this->tableOrderProd AS a 
            INNER JOIN $this->tableOrder AS b ON a.order_id=b.id   
            WHERE b.order_status=?  
            GROUP BY a.product_id)
        AS c ON a.id=c.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS spoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id 
            WHERE b.iscancel=?
            GROUP BY a.product_id)
        AS d ON a.id=d.product_id 
        WHERE a.id=?
        ORDER BY a.product_name";

        $params = [0, "paid", 0, $this->product_id];
        $result = $this->setRows($query, $params);

        return $result;
    }

    public function inventoryCount() {
        if ($this->category_id != 0) {
            $where = " AND c.id = ? ";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, 0, $this->category_id];
        } else {
            $where = "";
            $params = [0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, "paid", $this->from_date, $this->to_date, "paid", $this->to_date, "paid", $this->beginning_date, 0, $this->from_date, $this->to_date, 0, $this->to_date, 0, $this->beginning_date, 0];
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
        LEFT OUTER JOIN 
            (SELECT a.product_id, SUM(a.spoilage_qty) AS spoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=? 
            AND DATE(b.created_at)>=? 
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id) 
        AS f ON b.id=f.product_id
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sespoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id   
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS ensp ON b.id=ensp.product_id 
        LEFT OUTER JOIN
            (SELECT a.product_id, SUM(a.spoilage_qty) AS sbspoilage_qty 
            FROM $this->tableSpoilageProd AS a 
            INNER JOIN $this->tableSpoilage AS b ON a.spoilage_id=b.id  
            WHERE b.iscancel=?  
            AND DATE(b.created_at)<=? 
            GROUP BY a.product_id)
        AS begsp ON b.id=begsp.product_id 
        WHERE b.isdelete=?
        $where";

        $result = $this->setColumn($query, $params);

        return $result;
    }
}