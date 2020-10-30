<template>
    <div class="row no-gutters bg-light">
        <div class="col-12 mb-4 font-weight-bold text-center">
            Dated From : {{ fdate }}
            To : {{ tdate }}
        </div>
        <div class="col-12" v-if="$route.query.typeid == 1">
            <table class="table table-sm text-center">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Unit Cost</th>
                        <th>Sold</th>
                        <th>Total Cost</th>
                        <th>Total Price</th>
                        <th>Discount</th>
                        <th>Vat Amount</th>
                        <th>Total Amount</th>
                        <th>Total Profit</th>
                    </tr>
                </thead>
                <tbody v-for="sales_prod in sales_prods" :key="sales_prod.id">
                    <tr>
                        <td class="align-middle">{{ sales_prod.product_name }}</td>
                        <td class="align-middle">{{ sales_prod.category_name }}</td>
                        <td class="align-middle">&#8369; {{ sales_prod.unit_price }}</td>
                        <td class="align-middle">{{ sales_prod.tot_qty }}</td>
                        <td class="align-middle">&#8369; {{ (sales_prod.unit_price * sales_prod.tot_qty).toFixed(2) }}</td>
                        <td class="align-middle">&#8369; {{ sales_prod.tot_amount }}</td>
                        <td class="align-middle">&#8369; {{ sales_prod.tot_disc }}</td>
                        <td class="align-middle">&#8369; {{ sales_prod.tot_vat }}</td>
                        <td class="align-middle">&#8369; {{ (sales_prod.tot_amount - sales_prod.tot_vat - sales_prod.tot_disc).toFixed(2) }}</td>
                        <td class="align-middle">&#8369; {{ ((sales_prod.tot_amount - sales_prod.tot_vat - sales_prod.tot_disc) - (sales_prod.unit_price * sales_prod.tot_qty)).toFixed(2) }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr class="font-weight-bold">
                        <td colspan="4" class="text-right">TOTAL:</td>
                        <td class="text-center">&#8369; {{ unitCostTotal }}</td>
                        <td class="text-center">&#8369; {{ salesTotal }}</td>
                        <td class="text-center">&#8369; {{ discountTotal }}</td>
                        <td class="text-center">&#8369; {{ vatTotal }}</td>
                        <td class="text-center">&#8369; {{ totalAmount }}</td>
                        <td class="text-center">&#8369; {{ totalProfit }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12" v-else-if="$route.query.typeid == 2">
            <table class="table table-sm text-center">
                <thead>
                    <th>Category Name</th>
                    <th>Sold</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Total Price</th>
                    <th>Discount</th>
                    <th>Vat</th>
                    <th>Total Amount</th>
                    <th>Total Profit</th>
                </thead>
                <tbody v-for="sales_cat in sales_cats" :key="sales_cat.category_id">
                    <tr>
                        <td class="align-middle">{{ sales_cat.category_name }}</td>
                        <td class="align-middle">{{ sales_cat.tot_qty }}</td>
                        <td class="align-middle">&#8369; {{ sales_cat.unit_price }}</td>
                        <td class="align-middle">&#8369; {{ sales_cat.tot_cost }}</td>
                        <td class="align-middle">&#8369; {{ sales_cat.tot_amount }}</td>
                        <td class="align-middle">&#8369; {{ sales_cat.tot_disc }}</td>
                        <td class="align-middle">&#8369; {{ sales_cat.tot_vat }}</td>
                        <td class="align-middle">&#8369; {{ (sales_cat.tot_amount - sales_cat.tot_vat - sales_cat.tot_disc).toFixed(2) }}</td>
                        <td class="align-middle">&#8369; {{ ((sales_cat.tot_amount - sales_cat.tot_vat - sales_cat.tot_disc) - (sales_cat.tot_cost)).toFixed(2) }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr class="font-weight-bold">
                        <td colspan="3" class="text-right">TOTAL:</td>
                        <td class="text-center">&#8369; {{ unitCostTotal }}</td>
                        <td class="text-center">&#8369; {{ salesTotal }}</td>
                        <td class="text-center">&#8369; {{ discountTotal }}</td>
                        <td class="text-center">&#8369; {{ vatTotal }}</td>
                        <td class="text-center">&#8369; {{ totalAmount }}</td>
                        <td class="text-center">&#8369; {{ totalProfit }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import moment from "moment";
export default {
    data () {
        return {
            sales_cats: [],
            sales_prods: [],
            fdate: moment(this.$route.query.fdate).format('MMM DD, YYYY'),
            tdate: moment(this.$route.query.tdate).format('MMM DD, YYYY')
        }
    },
    created() {
        this.fetchSalesProduct();
    },
    methods: {
        fetchSalesProduct() {
            let app = this;
            const axios = require("axios");
                axios
                    .get("/api/getAllSalesProduct/?frdate=" + app.$route.query.fdate + "&tdate=" + app.$route.query.tdate)
                    .then(function(response) {
                        const responseData = response.data.data;
                        app.sales_prods = responseData.filter(item => item.tot_qty != 0);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                axios
                    .get("/api/getAllSalesCategory/?frdate=" + app.$route.query.fdate + "&tdate=" + app.$route.query.tdate)
                    .then(function(response) {
                        const responseData = response.data.data;
                        app.sales_cats = responseData.filter(item => item.tot_qty != 0);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
        },
    },
    computed: {
        salesTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_amount)
            });
            return (sum).toFixed(2);
        },
        unitCostTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.unit_price * element.tot_qty)
            });
            return (sum).toFixed(2);
        },
        vatTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_vat)
            });
            return sum.toFixed(2);
        },
        discountTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_disc)
            });
            return sum.toFixed(2);
        },
        totalAmount: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_amount - element.tot_vat - element.tot_disc)
            });
            return (sum).toFixed(2);
        },
        totalProfit: function() {
            return (this.totalAmount - this.unitCostTotal).toFixed(2);
        },
    }
}
</script>