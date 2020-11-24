<template>
    <div class="container" style="background-color: #ffffff">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        
            <div class="col-sm-6 mt-3"><strong>From:</strong> {{ fdate }}</div>
            <div class="col-sm-6 mt-3"><strong>To:</strong> {{ tdate }}</div>
            <div class="col-sm-6 mt-3"><strong>Category:</strong> {{ (categories != '') ? categories : 'All Categories' }}</div>
            <div class="col-sm-12 mt-4">
                <div class="card fullheight rounded-0">
                    <div class="card-body">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Beginning</th>
                                    <th>Purchase</th>
                                    <th>Sold</th>
                                    <th>Spoilage</th>
                                    <th>Ending</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom" v-for="inventory in inventories" :key="inventory.id">
                                    <td>{{ inventory.product_name }}</td>
                                    <td>{{ inventory.category_name }}</td>
                                    <td>{{ inventory.begstock_qty }}</td>
                                    <td>{{ inventory.purchase_qty }}</td>
                                    <td>{{ inventory.sales_qty }}</td>
                                    <td>{{ inventory.spoilage_qty }}</td>
                                    <td>{{ inventory.endstock_qty }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
export default {
    data () {
        return {
            categories: [],
            inventories: [],
            page: 1,
            fdate: moment(this.$route.query.fdate).format('MMM DD, YYYY hh:mm A'),
            tdate: moment(this.$route.query.tdate).format('MMM DD, YYYY hh:mm A')
        }
    },
    created () {
        this.fetchCategories(this.$route.query.catid);
        this.fetchInventory();
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        fetchCategories(catid) {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getCategoryName/?id=" + catid)
                .then(function(response) {
                    app.categories = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchInventory() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllInventories/?page=&fdate=" + this.$route.query.fdate + "&tdate=" + this.$route.query.tdate + "&category_id=" + this.$route.query.catid + "&product_name=" + this.$route.query.product_name)
                .then(function(response) {
                    app.inventories = response.data.data;
                    app.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>