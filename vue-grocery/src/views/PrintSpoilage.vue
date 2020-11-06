<template>
    <div class="container">
        <div class="row mx-auto">
            <div class="col-sm-12">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        
        <div class="row mx-auto">
            <div class="col-sm-12">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="spoilage_product in spoilage_products" :key="spoilage_product.id">
                            <td class="border-bottom">{{ spoilage_product.product_name }}</td>
                            <td class="border-bottom">{{ spoilage_product.spoilage_qty }}</td>
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
            spoilage_products: [],
            imgURL: 'http://localhost/grocery/public/images/'
        }
    },
    created() {
        this.fetchSpoilageProducts();
        this.fetchPurchases();
    },
    computed: {
    },
    methods: {
        purchaseDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchSpoilageProducts() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSpoilageProductsById/?spoilage_id=" + app.$route.query.spoilage_id)
                .then(function(response) {
                    console.log(response.data);
                    app.spoilage_products = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>