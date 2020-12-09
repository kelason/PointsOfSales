<template>
    <div class="row">
        <div class="col-md-2">
            <div class="card fullheight overflow-auto shadow">
                <div class="card-header bg-gradient p-4 pl-4 pr-4 pt-1">
                    <!-- <div class="form-label-group">
                        <input type="text" id="inputCategory" class="form-control form-control-sm mb-3 rounded-0 border-top-0 bg-transparent text-white border-left-0  border-right-0" placeholder="Insert Category" @keyup="searchCategory($event.target.value)">
                        <label for="inputCategory" class="text-white">Insert Category</label>
                    </div> -->
                    Category Types
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(category_type, index) in category_types" :value="category_type.id" :key="category_type.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{ 'active': (selectedCategoryType !== null) ? category_type.id == selectedCategoryType : index===0 }" @click="selectedCategoryType=category_type.id, fetchCategoriesByTypeId((selectedCategoryType !== null) ? selectedCategoryType : category_type.id), searchProd=''">{{ category_type.type_name }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary text-white btn-block sticky-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
        </div>
        <div class="col-md-7">
            <div class="card fullheight-v overflow-auto shadow">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="form-label-group">
                        <input type="text" v-model="searchProd" id="inputProduct" class="form-control form-control-sm bg-transparent text-white rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Insert Product" @keyup="searchProduct($event.target.value)">
                        <label for="inputProduct" class="text-white">Insert Product</label>
                    </div>
                </div>
                <div class="card-body pt-3">
                    
                    <div v-if="products==''">
                        <div class="text-danger text-center">
                            No Records...
                        </div>
                    </div>
                    <div v-else class="row text-center">
                        <div class="col-md-3 mb-4" v-for="(product) in products" :value="product.id" :key="product.id" @click="addOrderProducts(product.id, 1, product.stock_qty)">
                            <v-lazy-image :src="imgURL + product.product_image" class="res-img shadow-lg border" :title="product.product_name" />
                            <p class="card-text box text-white">
                                {{ trimProductName(product.product_name) }} <br> ({{ product.stock_qty }})
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-1">
                    <div class="container testimonial-group">
                        <div class="row">
                            <div class="col-xs-12"  v-if="categories==''">
                                <div class="text-danger text-center">
                                    No Records...
                                </div>
                            </div>
                            <div class="col-xs-1">
                                <button class="btn btn-sm btn-outline-dark m-1" v-for="(category, index) in categories" :key="category.id" :class="{ 'active': (selectedCategory !== null) ? category.id == selectedCategory : index===0 }" @click="selectedCategory=category.id, fetchProductsbyCategory((selectedCategory !== null) ? selectedCategory : category.id)">{{ category.category_name }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card fullheight shadow">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="form-label-group">
                        <input type="text" v-model="product_barcode" @input="SearchProductBarcode($event.target.value)" id="inputBarcode" ref="bar" class="form-control form-control-sm bg-transparent text-white rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Insert Barcode">
                        <label for="inputBarcode" class="text-white">Insert Barcode</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row overflow-auto" style="height: 48vh;">
                        <div class="col-12">
                            <table class="table table-striped">
                                <tbody>
                                    <tr v-for="(order, index) in orders" :key="order.id" :value="order.id">
                                        <td class="pt-2 pb-2 pl-2 align-middle" :title="'Click to discount ' + order.product_name" style="cursor: pointer;" @click="addDiscount(order.id)">{{order.product_name}}</td>
                                        <td class="pt-2 pb-2 align-middle"><input type="text" :min="1" :max="order.stock_qty" v-model="order.product_qty" @input="minInput($event, index), updateOrderProduct(order.id, order.product_qty, order.product_id)" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" style="width:40px;"></td>
                                        <td class="pt-2 align-middle"><b>&#8369; {{order.total_amount}}</b> <p class="small">{{(order.discount_amount == 0) ? '' : "-&#8369;" + order.discount_amount}}</p></td>
                                        <td class="pt-2 pb-2 align-middle" style="cursor: pointer;" :title="'Delete ' + order.product_name" @click="deleteOrderProduct(order.id)"><i class="fas fa-times-circle"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="height: 10vh;">
                        <div class="col-12">
                            <span class="float-left ml-2 font-weight-bold">Vat Sales:</span>
                            <span class="float-right mr-3 font-weight-bold">{{(vatSalesTotal == 0) ? '' : "&#8369; " + vatSalesTotal }}</span>
                        </div>
                        <div class="col-12">
                            <span class="float-left ml-2 font-weight-bold">12% Vat:</span>
                            <span class="float-right mr-3 font-weight-bold">{{(vatTotal == 0) ? '' : "&#8369; " + vatTotal }}</span>
                        </div>
                        <div class="col-12">
                            <span class="float-left ml-2 font-weight-bold">Discount Total:</span>
                            <span class="float-right mr-3 font-weight-bold">{{(discountTotal == 0) ? '' : "&#8369; " + discountTotal }}</span>
                        </div>
                        <div class="col-12">
                            <span class="float-left ml-2 font-weight-bold">Sub Total:</span>
                            <span class="float-right mr-3 font-weight-bold">{{(orderTotal == 0) ? '' : "&#8369; " + orderTotal }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-info btn-block sticky-bottom btn-lg rounded-0" @click="checkOut(orderTotal)"><span class="ml-2"><i class="fas fa-cart-plus"></i> Checkout</span> </button>
        </div>
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addDisc">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Discount</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Discount %</th>
                                            <th>Discount Amount</th>
                                            <th>Total Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order_disc, index) in order_discs" :key="order_disc.id" :value="order_disc.id">
                                            <td class="pt-2 pb-2 align-middle" :title="'Click to discount ' + order_disc.product_name" style="cursor: pointer;">{{order_disc.product_name}}</td>
                                            <td class="pt-2 pb-2 pl-5 align-middle">
                                                <input type="text" :min="0" :max="order_disc.max_qty" v-model="order_disc.product_qty" @input="minDiscQty($event, index)" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" style="width:40px;">
                                            </td>
                                            <td class="pt-2 pb-2 pl-5 align-middle">
                                                <input type="text" v-model="disc" min="0" max="90" @input="minInputDisc($event)" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" style="width:40px;">
                                            </td>
                                            <td class="pt-2 align-middle"><b>&#8369; {{(order_disc.product_qty * order_disc.selling_price) / (100 / disc)}}</b></td>
                                            <td class="pt-2 align-middle"><b>&#8369; {{order_disc.product_qty * order_disc.selling_price}}</b></td>
                                            <td class="pt-2 align-middle"><button type="button" class="btn btn-primary btn-sm" @click="btnDiscount(order_disc.id, (order_disc.product_qty * order_disc.selling_price) / (100 / disc))">Save</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
            product_barcode: "",
            products: [],
            categories: [],
            category_types: [],
            orders: [],
            order_discs: [],
            disc: 20,
            show: false,
            active: false,
            selectedCategory: null,
            selectedCategoryType: null,
            catid: undefined,
            searchProd: '',
            imgURL: 'http://localhost/grocery/public/images/products/'
        }
    },
    created () {
        this.focusBarcode();
        this.fetchCategoriesByTypeId();
        this.fetchCategoryTypes();
        this.fetchProductsbyCategory();
        this.fetchOrderProducts();
    },
    methods: {
        focusBarcode() {
            setTimeout(() => {
                this.$refs.bar.focus()
            }, 1)
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        back() {
            this.$router.push("/");
        },
        trimProductName(name) {
            var maxLength = 15;
            var trimString = name.length > maxLength ? name.substring(0, name.length - 3) + "..." : name;
            return trimString;
        },
        minInputDisc(event) {
            var app = this;
            const inputValue = parseInt(event.target.value);
            const minValue = parseInt(event.target.min);
            const maxValue = parseInt(event.target.max);
            
            if (inputValue < minValue || Number.isNaN(inputValue)) {
                return app.disc = 0;
            } else if (inputValue > maxValue) {
                return app.disc = maxValue;
            } else {
                return app.disc = inputValue;
            }
        },
        minDiscQty(event, selectedIndex) {
            var app = this;
            const inputValue = parseInt(event.target.value);
            const minValue = parseInt(event.target.min);
            const maxValue = parseInt(event.target.max);
            
            if (inputValue < minValue || Number.isNaN(inputValue)) {
                return app.order_discs[selectedIndex].product_qty = 0;
            } else if (inputValue > maxValue) {
                return app.order_discs[selectedIndex].product_qty = maxValue;
            } else {
                return app.order_discs[selectedIndex].product_qty = inputValue;
            }
        },
        minInput(event, selectedIndex) {
            var app = this;
            const inputValue = parseInt(event.target.value);
            const minValue = parseInt(event.target.min);
            const maxValue = parseInt(event.target.max);
            
            if (inputValue < minValue || Number.isNaN(inputValue)) {
                return app.orders[selectedIndex].product_qty = 0;
            } else if (inputValue > maxValue) {
                return app.orders[selectedIndex].product_qty = maxValue;
            } else {
                return app.orders[selectedIndex].product_qty = inputValue;
            }
        },
        fetchProductsbyCategory(id) {
            var app = this;
            var catid = '';
            const axios = require("axios");
            
            (id === undefined) ? catid = 0 : catid = id;
            app.loading = true;
            axios
                .get("/api/getProductsByCategory/?catid=" + catid)
                .then(function(response) {
                    app.products = response.data.data.filter(function(element){
                        return element.stock_qty != 0
                    });
                    app.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCategoriesByTypeId(id) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllCategoriesByTypeId/?category_status=active&typeid=" + id)
                .then(function(response) {
                    app.categories = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCategoryTypes() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllCategoryTypes/")
                .then(function(response) {
                    app.category_types = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchOrderProducts() {
            var app = this;
            const axios = require("axios");
            axios
                .get("/api/getAllOrderProducts/")
                .then(function(response) {
                    app.orders = response.data.data;
                    app.orders = response.data.data.filter(function(element){
                        return element.id != null
                    });
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
                    .get("/api/searchCategory/?category_name=" + name)
                    .then(function(response) {
                        app.categories = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
        },
        searchProduct(name) {
            var app = this;
            const axios = require("axios");
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchProductByCategory/?product_name=" + name + "&category_id=" + app.selectedCategory)
                    .then(function(response) {
                        app.products = response.data.data.filter(function(element){
                            return element.stock_qty != 0
                        });
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
                
        },
        addOrderProducts(id, qty, stock) {
            var app = this;
            const axios = require("axios");

            if (stock != 0) {
                axios
                    .get("/api/addOrderProducts/?id=" + id + '&qty=' + qty + '&user_id=' + app.$session.get('user_id'))
                    .then(() => {
                        app.fetchOrderProducts();
                        app.fetchProductsbyCategory(app.selectedCategory);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        updateOrderProduct(id, qty, product_id) {
            var app = this;
            const axios = require("axios");
            axios
                .put("/api/updateOrderProduct/" , {"id": id, "qty": qty, "product_id": product_id})
                .then(() => {
                    app.fetchOrderProducts();
                    app.fetchProductsbyCategory(app.selectedCategory);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteOrderProduct(id) {
            var app = this;
            const axios = require("axios");
            axios
                .delete("/api/deleteOrderProduct/?id=" + id)
                .then(() => {
                    app.fetchOrderProducts();
                    app.fetchProductsbyCategory(app.selectedCategory);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addDiscount(order_id) {
            var app = this;
            const axios = require("axios");
            axios
                .get("/api/getOrderDiscountById/?order_id=" + order_id)
                .then((res) => {
                    console.log(res.data)
                    app.toggleModal();
                    app.order_discs = res.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        btnDiscount(id, disc) {
            var app = this;
            const axios = require("axios");
            axios
                .get("/api/updateOrderDiscountById/?id=" + id + "&disc=" + disc)
                .then(() => {
                    app.fetchOrderProducts();
                    app.toggleModal();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        checkOut(orderTotal) {
            (orderTotal != 0) ? this.$router.push("/terminal/payment") : alert('Please punch some products before clicking checkout');
        },
        SearchProductBarcode(barcode) {
            var app = this;
            const axios = require("axios");

            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                .get("/api/searchProductByBarcode/?barcode=" + barcode + '&qty=1&user_id=' + app.$session.get('user_id'))
                .then(() => {
                    app.fetchOrderProducts();
                    app.fetchProductsbyCategory(app.selectedCategory);
                    app.product_barcode = '';
                })
                .catch((error) => {
                    console.log(error);
                });
            }, 500);

            
        }
    },
    computed: {
        orderTotal: function() {
            var sum=0;
            this.orders.forEach(element => {
                sum += parseFloat(element.total_amount)
            });
            return (sum - this.discountTotal).toFixed(2);
        },
        vatTotal: function() {
            var sum=0;
            this.orders.forEach(element => {
                sum += parseFloat(element.vat_amount)
            });
            return sum.toFixed(2);
        },
        discountTotal: function() {
            var sum=0;
            this.orders.forEach(element => {
                sum += parseFloat(element.discount_amount)
            });
            return sum.toFixed(2);
        },
        vatSalesTotal: function() {
            return (this.orderTotal - this.vatTotal - this.discountTotal).toFixed(2);
        }
    }
}
</script>