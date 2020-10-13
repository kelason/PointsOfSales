<template>
    <div class="row">
        <div class="col-md-2">
            <div class="card fullheight overflow-auto shadow">
                <div class="card-body">
                    <input type="text" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Insert Category">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(category, index) in categories" :value="category.id" :key="category.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{ 'active': (selectedCategory !== undefined) ? category.id == selectedCategory : index===0 }" @click="selectedCategory=category.id">{{ category.category_name }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card fullheight overflow-auto shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="text" ref="bar" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Insert Product/Barcode">
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-3 mb-4" v-for="(product) in products" :value="product.id" :key="product.id">
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
            <div class="card fullheight overflow-auto shadow">
                <div class="card-body">
                    <button class="btn btn-dark btn-block item-bottom btn-lg rounded-0">Checkout <i class="fas fa-cart-plus"></i></button>
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
            selectedCategory: undefined,
            imgURL: 'http://localhost/grocery/public/images/products/'
        }
    },
    created () {
        this.focusInputs();
        this.fetchProducts();
        this.fetchCategories();
    },
    methods: {
        //Focus Product Barcode input when page load 
        focusInputs() {
            setTimeout(() => {
                this.$refs.bar.focus()
            }, 1)
        },
        fetchProducts() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllProducts.php")
                .then(function(response) {
                    app.products = response.data.data;
                    //console.log(response.data.data);
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
                    //console.log(response.data.data);
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