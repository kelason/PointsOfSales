<template>
    <div class="container" style="background-color: #ffffff">
        <div class="col-sm-12 text-center">
            <h2>Generic POS</h2>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <p><b>Cashier: </b>{{ sales.employee_fn + ' ' + sales.employee_sn }}</p> 
                </div>
                <div class="col-sm-6">
                    <p><b>Customer: </b>{{ sales.customer_name }}</p> 
                </div>
                <div class="col-sm-6">
                    <p><b>Payment: </b>{{ sales.payment_name }}</p> 
                </div>
                <div class="col-sm-6">
                    <p><b>Date: </b>{{ dtFormat(sales.created_at) }}</p> 
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <table class="table table-sm text-center">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Vat</th>
                        <th>Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody v-for="sales_order in sales_orders" :key="sales_order.sales_id">
                    <tr>
                        <td>{{ sales_order.product_name }}</td>
                        <td>{{ sales_order.product_qty }}</td>
                        <td>&#8369; {{ sales_order.vat_amount }}</td>
                        <td>&#8369; {{ sales_order.discount_amount }}</td>
                        <td>&#8369; {{ sales_order.total_amount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row mx-auto mt-5">
            <div class="col-sm-4 signature"></div>
        </div>
        <div class="row mx-auto">
            <div class="col-sm-4">
                Checked By:
            </div>
        </div>
    </div>

</template>
<script>
import moment from "moment";
import axios from "axios";
export default {
  data () {
    return {
      sales_orders: [],
      sales: []
    }
  },
  created() {
      this.fetchSalesOrder();
      this.fetchSalesInvoiceById();
  },
  methods: {
      dtFormat(dt) {
        return moment(dt).format('MMM DD, YYYY hh:mm A')
      },
      fetchSalesOrder() {
            let app = this;

            axios
                .get("/api/getSalesOrderById/?sales_id=" + this.$route.query.sales_id)
                .then(function(response) {
                    app.sales_orders = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSalesInvoiceById() {
            let app = this;

            axios
                .get("/api/getSalesInvoiceById/?id=" + this.$route.query.sales_id)
                .then(function(response) {
                    console.log(response.data);
                    app.sales = response.data[0];
                })
                .catch((error) => {
                    console.log(error);
                });
        },
  }
}
</script>