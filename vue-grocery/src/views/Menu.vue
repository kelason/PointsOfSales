<template>
    <div class="row">
        <div class="col-md-2">
            <div class="card fullheight overflow-auto shadow">
                <div class="card-body">
                    <div class="form-label-group">
                        <input type="text" id="inputCategory" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Insert Category" @keyup="searchCategory($event.target.value)">
                        <label for="inputCategory">Insert Category</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(category, index) in categories" :value="category.id" :key="category.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{ 'active': (selectedCategory !== undefined) ? category.id == selectedCategory : index===0 }" @click="selectedCategory=category.id, fetchProductsbyCategory((selectedCategory !== undefined) ? selectedCategory : category.id)">{{ category.category_name }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card fullheight overflow-auto shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-label-group">
                                <input type="text" id="inputProduct" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Insert Product">
                                <label for="inputProduct">Insert Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center" v-if="loading">
                        <img src="http://localhost/grocery/public/images/loading.gif" alt="loading gif">
                    </div>
                    <div v-else class="row text-center">
                        <div class="col-md-3 mb-4" v-for="(product) in products" :value="product.id" :key="product.id" @click="addQueueItems(product.id, 1)">
                            <v-lazy-image :src="imgURL + product.product_image" class="res-img shadow border"/>
                            <p class="card-text box text-white mr-4 ml-4">
                                {{ product.product_name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card fullheight shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-label-group">
                                <input type="text" id="inputBarcode" ref="bar" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Insert Barcode">
                                <label for="inputBarcode">Insert Barcode</label>
                            </div>
                        </div>
                    </div>
                    <div class="row overflow-auto border" style="height: 65vh;">
                        <div class="col-12">
                        </div>
                    </div>
                    <button class="btn btn-dark btn-block item-bottom btn-lg rounded-0"><i class="fas fa-cart-plus"></i> Checkout</button>
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
            categories: [],
            queue: [],
            selectedCategory: undefined,
            catid: undefined,
            loading: false,
            imgURL: 'http://localhost/grocery/public/images/products/'
        }
    },
    created () {
        this.focusBarcode();
        this.fetchCategories();
        this.fetchProductsbyCategory();
    },
    methods: {
        focusBarcode() {
            setTimeout(() => {
                this.$refs.bar.focus()
            }, 1)
        },
        fetchProductsbyCategory(id) {
            var app = this;
            var catid = '';
            const axios = require("axios");
            
            (id === undefined) ? catid = 0 : catid = id;
            app.loading = true;
            axios
                .get("/api/getProductsByCategory.php?catid=" + catid)
                .then(function(response) {
                    app.products = response.data.data;
                    app.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCategories() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllCategories.php")
                .then(function(response) {
                    app.categories = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        searchCategory(name) {
            var app = this;
            const axios = require("axios");
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchCategory.php?category_name=" + name)
                    .then(function(response) {
                        app.categories = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
                
        },
        addQueueItems(id, qty) {
            var app = this;
            const axios = require("axios");
            axios
                .get("/api/addQueueItems.php?id=" + id + '&qty=' + qty)
                .then(function(response) {
                    app.queue = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    computed: {
    }
}
</script>