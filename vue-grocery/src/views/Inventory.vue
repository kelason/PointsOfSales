<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="text" v-model="product_name" id="inputProduct" class="form-control form-control-sm mb-3 rounded-0 border-top-0 bg-transparent text-white border-left-0  border-right-0" placeholder="Insert Category" @keyup="fetchInventory(page, $event.target.value, category_id, from_date, to_date)">
                                <label for="inputProduct" class="text-white">Search Product/Barcode</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <select v-model="category_id" @change="fetchInventory(page, product_name, $event.target.value, from_date, to_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent mt-3 border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="0">All Categories</option>
                                <option class="text-dark" v-for="category in categories" :key="category.id" :value="category.id">{{ category.category_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" @input="fetchInventory(page, product_name, category_id, $event.target.value, to_date)" v-model="from_date" id="from_date" class="form-control form-control-sm text-white mb-3 bg-transparent rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                <label for="from_date" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" @input="fetchInventory(page, product_name, category_id, from_date, $event.target.value)" v-model="to_date" id="to_date" class="form-control form-control-sm text-white mb-3 bg-transparent rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                <label for="to_date" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Beginning</th>
                                <th>Purchase</th>
                                <th>Sold</th>
                                <th>Spoilages</th>
                                <th>Ending</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr>
                                <td colspan="7"><img src="/grocery/public/images/loading.gif" alt=""></td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-if="!inventories.length">
                                <td colspan="7"><strong class="text-danger text-center">No Record</strong></td>
                            </tr>
                            <tr v-else class="border-bottom" v-for="inventory in inventories" :key="inventory.id">
                                <td>{{ inventory.product_name }}</td>
                                <td>{{ inventory.category_name }}</td>
                                <td>{{ inventory.begstock_qty }}</td>
                                <td>{{ inventory.purchase_qty }}</td>
                                <td>{{ inventory.sales_qty }}</td>
                                <td>{{ inventory.spoilage_qty }}</td>
                                <td :class="{'text-white bg-danger': inventory.endstock_qty <= inventory.alarmlvl }">{{ inventory.endstock_qty }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row item-bottom pl-4 pr-4">
                        <div class="col-4">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-4 text-center">
                            <button class="btn btn-sm btn-primary" @click="printInventory()"><span class="float-left ml-2"><i class="fas fa-print"></i> Print All</span></button>
                        </div>
                        <div class="col-4">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1 || pagination == ''}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchInventory(page = page - 1, product_name, category_id, from_date, to_date)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchInventory(page = page + 1, product_name, category_id, from_date, to_date)">
                                            Next
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data () {
        return {
            categories: [],
            pagination: [],
            inventories: [],
            product_name: '',
            category_id: 0,
            loading: false,
            from_date: new Date(),
            to_date: new Date(),
            page: 1,
            imgURL: '/grocery/public/images/products/'
        }
    },
    created () {
        this.fetchCategories();
        this.from_date = moment().format("YYYY-MM-DDTHH:mm");
        this.to_date = moment().format("YYYY-MM-DDTHH:mm");
        this.fetchInventory(this.page, this.product_name, this.category_id, this.from_date, this.to_date);
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
        fetchInventory(page, product_name, category_id, from_date, to_date) {
            var app = this;
            const axios = require("axios");
            app.loading = true;

            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/getAllInventories/?page=" + page + "&product_name=" + product_name + "&category_id=" + category_id + "&fdate=" + from_date + "&tdate=" + to_date)
                    .then(function(response) {
                        console.log(response.data);
                        app.inventories = response.data.data;
                        app.pagination = response.data.pagination;
                        app.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 200);
        },
        printInventory() {
            let routeData = this.$router.resolve({name: 'Print Inventory', query: {fdate: this.from_date, tdate: this.to_date, catid: this.category_id, product_name: this.product_name}});
            window.open(routeData.href, '_blank', "height=500,width=900");
        }
    }
}
</script>