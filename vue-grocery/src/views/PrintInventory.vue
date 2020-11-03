<template>
    <div class="row no-gutters">
        <div class="col-sm-12">
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
</template>
<script>
export default {
    data () {
        return {
            categories: [],
            inventories: [],
            page: 1
        }
    },
    created () {
        this.fetchCategories();
        this.fetchInventory();
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        fetchCategories() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllCategories/?page=&status=active")
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
                .get("/api/getAllInventories/?page=&fdate=" + this.$route.query.fdate + "&tdate=" + this.$route.query.tdate)
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