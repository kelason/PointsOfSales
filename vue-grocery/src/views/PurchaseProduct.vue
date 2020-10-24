<template>
    <div class="container">
        <div class="row mx-auto">
            <div class="col-sm-12">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        
        <div class="row mx-auto" v-for="purchase in purchases" :key="purchase.id">
            <img :src="imgURL + 'cancelled.png'" style="position:absolute; width: 600px; z-index: 10;" v-if="purchase.iscancel == 1">
            <div class="col-sm-6"><strong>Supplier:</strong> {{ purchase.supplier_name }}</div>
            <div class="col-sm-6"><strong>Date:</strong> {{ purchaseDtFormat(purchase.created_at) }}</div>
            <div class="col-sm-6"><strong>Delivery Reciept:</strong> {{ purchase.drnum }}</div>
            <div class="col-sm-6"><strong>Cashier:</strong> {{ purchase.employee_fn }} {{ purchase.employee_sn }}</div>
        </div>
        <br><br>
        <div class="row mx-auto">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="purchase_product in purchase_products" :key="purchase_product.id">
                            <td>{{ purchase_product.product_name }}</td>
                            <td>{{ purchase_product.purchase_qty }}</td>
                            <td>&#8369; {{ purchase_product.total_amount }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold pr-3">Total:</td>
                            <td>&#8369; {{totalPurchase}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data () {
        return {
            msg: '',
            errors: [],
            purchase_products: [],
            purchases: [],
            imgURL: 'http://localhost/grocery/public/images/'
        }
    },
    created() {
        this.fetchPurchaseProducts();
        this.fetchPurchases();
    },
    computed: {
        totalPurchase: function() {
            var sum=0;
            this.purchase_products.forEach(element => {
                sum += parseFloat(element.total_amount)
            });
            return sum.toFixed(2);
        }
    },
    methods: {
        purchaseDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchPurchaseProducts() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllPurchaseProductsById/?purchase_id=" + app.$route.query.purchase_id)
                .then(function(response) {
                    app.purchase_products = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchPurchases() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllPurchasesById/?purchase_id=" + app.$route.query.purchase_id)
                .then(function(response) {
                    app.purchases = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    }
}
</script>