<template>
    <div class="container" style="background-color: #ffffff">
        <div class="row mx-auto">
            <div class="col-sm-12 text-center">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        <img :src="imgURL + 'cancelled.png'" style="position:absolute; width: 600px; z-index: 10;" v-if="spoilage.iscancel == 1">
        <div class="row mx-auto">
            <div class="col-sm-6"><strong>Remitted By:</strong> {{ spoilage.employee_fn + " " + spoilage.employee_sn }}</div>
            <div class="col-sm-6"><strong>Date:</strong> {{ spoilageDtFormat(spoilage.created_at) }}</div>
        </div>
        <br>
        <br>
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
                        <tr class="border-bottom" v-for="spoilage_product in spoilage_products" :key="spoilage_product.id">
                            <td class="border-bottom">{{ spoilage_product.product_name }}</td>
                            <td class="border-bottom">{{ spoilage_product.spoilage_qty }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mx-auto mt-4">
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
import moment from 'moment';
export default {
    data () {
        return {
            spoilage: [],
            spoilage_products: [],
            imgURL: 'http://localhost/grocery/public/images/'
        }
    },
    created() {
        this.fetchSpoilage();
        this.fetchSpoilageProducts();
    },
    computed: {
    },
    methods: {
        spoilageDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchSpoilage() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getSpoilageById/?spoilage_id=" + app.$route.query.spoilage_id)
                .then(function(response) {
                    app.spoilage = response.data[0];
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSpoilageProducts() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSpoilageProductsById/?spoilage_id=" + app.$route.query.spoilage_id)
                .then(function(response) {
                    app.spoilage_products = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>