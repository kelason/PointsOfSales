<?php

class Sales extends Database
{
    public $id;
    public $order_id;
    public $payment_typeid;
    public $customer_id;
    public $cashier_id;
    public $remit_id;
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
    public $sales_status;
    private $table = "possales";
    private $tableOrder = "posorders";
    private $tableOrderProd = "posorder_products";
    private $tableProd = "posproducts";
    private $tableProdCat = "posproduct_categories";
    private $tableCat = "poscategories";
    private $tableCus = "poscustomers";
    private $tablePay = "pospayment_types";
    private $tableEmp = "posemployees";

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

    public function updateSalesInvoice() {
        $query = "UPDATE $this->table SET sales_status=:sales_status WHERE id=:id";
        $fields = [
            "sales_status" => "void",
            "id" => $this->id
        ];

        return $this->update($query, $fields);
    }

    public function remitSales() {
        $query = "UPDATE $this->table 
        SET 
            sales_status=:sales_status, 
            remit_id=:remit_id 
        WHERE 
            sales_status=:sls_status 
        AND 
            remit_id=:r_id";
        $fields = [
            "sales_status" => $this->sales_status,
            "remit_id" => $this->remit_id,
            "sls_status" => "not remitted",
            "r_id" => 0
        ];

        return $this->update($query, $fields);
    }

    public function cancelRemitSales() {
        $query = "UPDATE $this->table 
        SET 
            sales_status=:sales_status, 
            remit_id=:remit_id 
        WHERE 
            sales_status=:sls_status 
        AND 
            remit_id=:r_id";
        $fields = [
            "sales_status" => $this->sales_status,
            "remit_id" => 0,
            "sls_status" => "remitted",
            "r_id" => $this->remit_id
        ];

        return $this->update($query, $fields);
    }

    public function getSalesByDate() {
        if ($this->employee_id == 0) {
            $where = "";
            $params = [$this->from_date, $this->to_date, $this->order_status, "void"];
        } else {
            $where = " AND a.cashier_id=? ";
            $params = [$this->from_date, $this->to_date, $this->order_status, "void", $this->employee_id];
        }

        $query = "SELECT 
        SUM(b.total_amount - b.discount_amount - b.vat_amount) AS sales_total, 
        DATE(a.created_at) AS sales_date
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProd AS b ON a.id=b.order_id 
        INNER JOIN $this->table AS c ON c.order_id=a.id 
        WHERE DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?
        AND a.order_status=?
        AND c.sales_status!=?
        $where
        GROUP BY DATE(a.created_at)";

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getSalesBySingleDate() {
        if ($this->employee_id == 0) {
            $where = "";
            $params = [$this->from_date, $this->order_status, "void"];
        } else {
            $where = " AND a.cashier_id=? ";
            $params = [$this->from_date, $this->order_status, "void", $this->employee_id];
        }

        $query = "SELECT 
        SUM(b.total_amount - b.discount_amount - b.vat_amount) AS sales_total, 
        DATE(a.created_at) AS sales_date
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProd AS b ON a.id=b.order_id 
        INNER JOIN $this->table AS c ON c.order_id=a.id 
        WHERE DATE(a.created_at)=?
        AND a.order_status=?
        AND c.sales_status!=?
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
        INNER JOIN $this->tableOrderProd AS b ON a.id=b.order_id 
        INNER JOIN $this->tableProd AS c ON c.id=b.product_id 
        INNER JOIN $this->table AS d ON d.order_id=a.id 
        WHERE DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?
        AND a.order_status=?
        AND d.sales_status!=?
        GROUP BY b.product_id
        LIMIT 5";

        $params = [$this->from_date, $this->to_date, $this->order_status, "void"];

        $result = $this->setRows($query, $params);

        return $result;
    }

    public function getAllSalesProduct() {
        $query = "SELECT 
        d.id AS order_id, 
        b.id AS product_id, 
        b.product_name, 
        b.unit_price, 
        b.selling_price, 
        c.category_name, 
        COALESCE(d.tot_qty,0) AS tot_qty, 
        COALESCE(d.tot_vat,0) AS tot_vat, 
        COALESCE(d.tot_disc,0) AS tot_disc, 
        COALESCE(d.tot_amount,0) AS tot_amount
        FROM $this->tableProdCat AS a
        INNER JOIN $this->tableProd AS b ON b.id=a.product_id
        INNER JOIN $this->tableCat AS c ON c.id=a.category_id
        LEFT OUTER JOIN 
            (SELECT 
                a.id, 
                b.product_id, 
                SUM(b.product_qty) AS tot_qty, 
                SUM(b.vat_amount) AS tot_vat, 
                SUM(b.discount_amount) AS tot_disc, 
                SUM(b.total_amount) AS tot_amount
            FROM $this->tableOrder AS a 
            INNER JOIN $this->tableOrderProd AS b ON a.id=b.order_id
            INNER JOIN $this->table AS c ON c.order_id=a.id 
            WHERE a.order_status=? 
            AND DATE(a.created_at)>=?
            AND DATE(a.created_at)<=?
            AND c.sales_status!=?
            GROUP BY b.product_id) 
        AS d ON d.product_id=a.product_id";

        $params = [$this->order_status, $this->from_date, $this->to_date, "void"];

        return $this->setRows($query, $params);
    }

    public function getAllSalesCategory() {
        $query = "SELECT 
            e.id,
            e.category_name, 
            COALESCE(SUM(d.unit_price),0) AS unit_price,
            COALESCE(SUM(d.unit_price * b.product_qty),0) AS tot_cost,
            COALESCE(SUM(b.product_qty),0) AS tot_qty, 
            COALESCE(SUM(b.vat_amount),0) AS tot_vat, 
            COALESCE(SUM(b.discount_amount),0) AS tot_disc, 
            COALESCE(SUM(b.total_amount),0) AS tot_amount 
        FROM $this->tableOrder AS a 
        INNER JOIN $this->tableOrderProd AS b ON a.id=b.order_id
        INNER JOIN $this->tableProdCat AS c ON c.product_id=b.product_id
        INNER JOIN $this->tableProd AS d ON d.id=b.product_id
        INNER JOIN $this->tableCat AS e ON e.id=c.category_id
        INNER JOIN $this->table AS f ON f.order_id=a.id 
        WHERE a.order_status=? 
        AND DATE(a.created_at)>=?
        AND DATE(a.created_at)<=?
        AND f.sales_status!=?
        GROUP BY c.category_id"; 

        $params = [$this->order_status, $this->from_date, $this->to_date, "void"];

        return $this->setRows($query, $params);
    }

    public function getAllSalesInvoice() {
        $query = "SELECT 
            a.id, 
            a.sales_status,
            a.sales_comment, 
            a.created_at, 
            b.payment_name, 
            c.customer_name,
            d.employee_fn,
            d.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tablePay AS b ON b.id=a.payment_typeid
        INNER JOIN $this->tableCus AS c ON c.id=a.customer_id
        INNER JOIN $this->tableEmp AS d ON d.id=a.cashier_id
        AND a.created_at>=?
        AND a.created_at<=?
        AND a.payment_typeid=? 
        AND a.customer_id=?";

        $params = [$this->from_date, $this->to_date, $this->payment_typeid, $this->customer_id];
        return $this->setRows($query, $params);
    }

    public function getSalesInvoiceById() {
        $query = "SELECT 
            a.id, 
            a.sales_status,
            a.sales_comment, 
            a.created_at, 
            b.payment_name, 
            c.customer_name,
            d.employee_fn,
            d.employee_sn
        FROM $this->table AS a
        INNER JOIN $this->tablePay AS b ON b.id=a.payment_typeid
        INNER JOIN $this->tableCus AS c ON c.id=a.customer_id
        INNER JOIN $this->tableEmp AS d ON d.id=a.cashier_id
        AND a.id=?";

        $params = [$this->id];
        return $this->setRow($query, $params);
    }

    public function getSalesOrderById() {
        $query = "SELECT 
            c.product_name, 
            a.product_qty, 
            a.total_amount, 
            a.vat_amount, 
            a.discount_amount
        FROM $this->tableOrderProd AS a
        INNER JOIN $this->table AS b ON b.order_id=a.order_id
        INNER JOIN $this->tableProd AS c ON c.id=a.product_id
        INNER JOIN $this->tableEmp AS d ON d.id=b.cashier_id
        WHERE b.id=?";

        $params = [$this->id];
        return $this->setRows($query, $params);
    }

    public function getAllSalesAmount() {
        $query = "SELECT 
            total_amount
        FROM $this->table
        WHERE
        sales_status=?";

        $params = ["not remitted"];
        return $this->setRows($query, $params);
    }
}