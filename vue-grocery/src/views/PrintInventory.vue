<template>
    <div class="container-fluid px-4" style="background-color: #ffffff; min-height: 100vh;">
        <div class="row pt-3">
            <div class="col-12 text-center">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4"><strong>From:</strong> {{ fdate }}</div>
            <div class="col-sm-4"><strong>To:</strong> {{ tdate }}</div>
            <div class="col-sm-4"><strong>Category:</strong> {{ (categories != '') ? categories : 'All Categories' }}</div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-bordered text-center" style="font-size: 0.82rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Beginning</th>
                            <th>Purchase</th>
                            <th>Sold</th>
                            <th>Spoilage</th>
                            <th>Change Items</th>
                            <th>Ending</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="inventory in inventories" :key="inventory.id">
                            <td class="text-left">{{ inventory.product_name }}</td>
                            <td>{{ inventory.category_name }}</td>
                            <td>{{ inventory.begstock_qty }}</td>
                            <td>{{ inventory.purchase_qty }}</td>
                            <td>{{ inventory.sales_qty }}</td>
                            <td>{{ inventory.spoilage_qty }}</td>
                            <td>{{ inventory.change_qty }}</td>
                            <td>{{ inventory.endstock_qty }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-sm-4">
                <div style="border-top: 1px solid #000; margin-top: 40px; padding-top: 4px;">Checked By:</div>
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