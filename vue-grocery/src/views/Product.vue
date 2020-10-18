<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient">
                    <h5>Manage Products</h5>
                </div>
                <div class="card-body overflow-auto">
                    <div class="form-label-group">
                        <input type="text" id="searchProduct" class="col-5 form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product">
                        <label for="searchProduct">Search Product</label>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Image</th>
                                <th>Name</th>
                                <th>Selling Price</th>
                                <th>Unit Price</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Barcode</th>
                                <th>Alarm Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="loading">
                            <tr class="text-center">
                                <td colspan="9">
                                    <img src="http://localhost/grocery/public/images/loading.gif" alt="loading gif">
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr class="text-center" v-for="product in products" :key="product.id">
                                <td>
                                    <v-lazy-image :src="imgURL + product.product_image" class="res-img shadow border" style="width: 80px; height: 50px;"/>
                                </td>
                                <td class="align-middle">{{ product.product_name }}</td>
                                <td class="align-middle">&#8369; {{ product.selling_price }}</td>
                                <td class="align-middle">&#8369; {{ product.unit_price }}</td>
                                <td class="align-middle">{{ product.category_name }}</td>
                                <td class="align-middle">{{ product.product_status }}</td>
                                <td class="align-middle">{{ product.barcode }}</td>
                                <td class="align-middle">{{ product.alarmlvl }}</td>
                                <td class="align-middle">
                                    <i class="fa fa-edit mr-2" :title="'Click to Edit ' + product.product_name" style="cursor: pointer;"></i> 
                                    <i class="fa fa-trash ml-2" :title="'Click to Delete ' + product.product_name" style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-warning" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchProducts(page = page - 2)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchProducts(page = page + 2)">
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
import VLazyImage from "v-lazy-image";
export default {
    components: {
        VLazyImage
    },
    data () {
        return {
            products: [],
            pagination: [],
            loading: false,
            page: 0,
            imgURL: 'http://localhost/grocery/public/images/products/'
        }
    },
    created () {
        this.fetchProducts();
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        fetchProducts(page) {
            var app = this;
            const axios = require("axios");
            
            app.loading = true;
            axios
                .get("/api/getAllProducts.php?page=" + page)
                .then(function(response) {
                    app.products = response.data.data;
                    app.pagination = response.data.pagination;
                    app.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>