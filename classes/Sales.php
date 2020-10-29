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
    public $from_date;
    public $to_date;
    public $order_status;
    public $employee_id;
    private $table = "possales";
    private $tableOrder = "posorders";
    private $tableOrderProducts = "posorder_products";
    private $tableProducts = "posproducts";

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

    public function getSalesByDate() {
        if ($this->employee_id == 0) {
            $where = "";
            $params = [$this->from_date, $this->to_date, $this->order_status];
        } else {
            $where = " AND a.cashier_id=? ";
            $params = [$this->from_date, $this->to_date, $this->order_status, $this->employee_id];
        }

        $query = "SELECT 
        SUM(b.total_amount - b.discount_amount - b.vat_amount) AS sales_total, 
        DATE(a.created_at) AS sales_date
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProducts AS b ON a.id=b.order_id 
        WHERE DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?
        AND a.order_status=?
        $where
        GROUP BY DATE(a.created_at)";

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getSalesBySingleDate() {
        if ($this->employee_id == 0) {
            $where = "";
            $params = [$this->from_date, $this->order_status];
        } else {
            $where = " AND a.cashier_id=? ";
            $params = [$this->from_date, $this->order_status, $this->employee_id];
        }

        $query = "SELECT 
        SUM(b.total_amount - b.discount_amount - b.vat_amount) AS sales_total, 
        DATE(a.created_at) AS sales_date
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProducts AS b ON a.id=b.order_id 
        WHERE DATE(a.created_at)=?
        AND a.order_status=?
        $where
        GROUP BY DATE(a.created_at)";

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getSalesRankingByDate() {
        $query = "SELECT 
        SUM(b.product_qty) AS total_qty, 
        c.product_name
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProducts AS b ON a.id=b.order_id 
        INNER JOIN $this->tableProducts AS c ON c.id=b.product_id 
        WHERE DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?
        AND a.order_status=?
        GROUP BY b.product_id
        LIMIT 5";

        $params = [$this->from_date, $this->to_date, $this->order_status];

        $result = $this->setRows($query, $params);

        return $result;
    }
}