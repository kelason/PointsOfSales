<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="text" id="inputProduct" class="form-control form-control-sm mb-3 rounded-0 border-top-0 bg-transparent text-white border-left-0  border-right-0" placeholder="Insert Category" @keyup="searchProduct($event.target.value)">
                                <label for="inputProduct" class="text-white">Search Product/Barcode</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control form-control-sm rounded-0 text-white bg-transparent mt-3 border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="0">All Categories</option>
                                <option class="text-dark" v-for="category in categories" :key="category.id" :value="category.id">{{ category.category_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="from_date" id="from_date" class="form-control form-control-sm text-white mb-3 bg-transparent rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                <label for="from_date" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="to_date" id="to_date" class="form-control form-control-sm text-white mb-3 bg-transparent rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                <label for="to_date" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Beginning</th>
                                <th>Purchase</th>
                                <th>Sold</th>
                                <th>Return</th>
                                <th>Ending</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="inventory in inventories" :key="inventory.id">
                                <td>{{ inventory.product_name }}</td>
                                <td>{{ inventory.category_name }}</td>
                                <td>{{  }}</td>
                                <td>{{ inventory.purchase_qty }}</td>
                                <td>{{ inventory.sales_qty }}</td>
                                <td>{{ }}</td>
                                <td>{{ inventory.stock_qty }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1 || pagination == ''}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchInventory(page = page - 2)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchInventory(page = page + 2)">
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
            from_date: new Date(),
            to_date: new Date(),
            page: 0
        }
    },
    created () {
        this.fetchCategories(this.page);
        this.fetchInventory();
        this.from_date = moment().format("YYYY-MM-DDThh:mm");
        this.to_date = moment().format("YYYY-MM-DDThh:mm");
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
        fetchInventory(page) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllInventories/?page=" + page)
                .then(function(response) {
                    console.log(response.data);
                    app.inventories = response.data.data;
                    app.pagination = response.data.pagination;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>