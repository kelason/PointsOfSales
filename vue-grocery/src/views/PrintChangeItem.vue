<template>
    <div class="container" style="background-color: #ffffff">
        <div class="row mx-auto">
            <div class="col-sm-12 text-center">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        <img :src="imgURL + 'cancelled.png'" style="position:absolute; width: 600px; z-index: 10;" v-if="changeitem.iscancel == 1">
        <div class="row mx-auto">
            <div class="col-sm-6"><strong>Changed By:</strong> {{ changeitem.employee_fn + " " + changeitem.employee_sn }}</div>
            <div class="col-sm-6"><strong>Date:</strong> {{ changeitemDtFormat(changeitem.created_at) }}</div>
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
                        <tr class="border-bottom" v-for="changeitem_product in changeitem_products" :key="changeitem_product.id">
                            <td class="border-bottom">{{ changeitem_product.product_name }}</td>
                            <td class="border-bottom">{{ changeitem_product.change_qty }}</td>
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
            changeitem: [],
            changeitem_products: [],
            imgURL: '/grocery/public/images/'
        }
    },
    created() {
        this.fetchChangeItem();
        this.fetchChangeItemProducts();
    },
    computed: {
    },
    methods: {
        changeitemDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchChangeItem() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getChangeItemById/?changeitem_id=" + app.$route.query.changeitem_id)
                .then(function(response) {
                    app.changeitem = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchChangeItemProducts() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllChangeItemProductsById/?changeitem_id=" + app.$route.query.changeitem_id)
                .then(function(response) {
                    app.changeitem_products = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>
