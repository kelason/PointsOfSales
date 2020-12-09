<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-label-group">
                                <input type="text" id="searchProduct" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product" @keyup="searchProduct($event.target.value)">
                                <label for="searchProduct" class="text-white">Search Product</label>
                            </div>
                        </div>
                        <div class="col-1 offset-6 mt-3">
                            <i class="fas fa-plus-circle fa-2x" title="Add Products" @click="toggleModal(), edit = false, flush()" style="cursor: pointer;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body overflow-auto">
                    <form method="POST" enctype="multipart/form-data">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Selling Price</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Barcode</th>
                                    <th>Alarm Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="loading">
                                <tr>
                                    <td colspan="9"><img src="http://localhost/grocery/public/images/loading.gif" alt=""></td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr v-if="!products.length">
                                    <td colspan="9"><strong class="text-danger text-center">No Record</strong></td>
                                </tr>
                                <tr v-else class="border-bottom" v-for="(product, index) in products" :key="product.id">
                                    <td>
                                        <label :title="'Click to Upload Photo'" :for="'file' + index" class="border m-0" style="cursor: pointer;">
                                            <input type="file" ref="file" :id="'file' + index" class="form-control-file" @change="createImage(product.id, index)" hidden>
                                            <img :src="imgURL + product.product_image" class="res-img shadow border" style="width: 80px; height: 50px;"/>
                                        </label>
                                    </td>
                                    <td class="align-middle">{{ product.product_name }}</td>
                                    <td class="align-middle">&#8369; {{ product.unit_price }}</td>
                                    <td class="align-middle">&#8369; {{ product.selling_price }}</td>
                                    <td class="align-middle">{{ product.category_name }}</td>
                                    <td class="align-middle">{{ product.product_status }}</td>
                                    <td class="align-middle"><img :src="imgBarcode + '/' + product.id + '.png'" class="res-img" style="width: 100px; height: 30px;"/></td>
                                    <td class="align-middle">{{ product.alarmlvl }}</td>
                                    <td class="align-middle">
                                        <i class="fa fa-edit mr-2" :title="'Click to Edit ' + product.product_name" style="cursor: pointer;" @click="editProduct(product)"></i> 
                                        <i class="fa fa-trash ml-2" :title="'Click to Delete ' + product.product_name" style="cursor: pointer;" @click="deleteProduct(product.id, product.product_name)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <hr>
                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1 || pagination == ''}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchProducts(page = page - 1)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchProducts(page = page + 1)">
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
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addProduct">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Product</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="product.product_name" id="prodname" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Product Name">
                                    <label for="prodname">Product Name</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.product_name">{{ errors.product_name }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="catid" v-model="product.category_id" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.category_name }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.category_id">{{ errors.category_id }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="product.unit_price" min="1" id="uprice" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Unit Price">
                                    <label for="uprice">Unit Price</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.unit_price">{{ errors.unit_price }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="product.selling_price" min="1" id="sprice" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Selling Price">
                                    <label for="sprice">Selling Price</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.selling_price">{{ errors.selling_price }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="catid" v-model="product.product_status" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.product_status">{{ errors.product_status }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="product.alarmlvl" min="1" id="alarm" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Alarm Level">
                                    <label for="alarm">Alarm Level</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert" v-if="msg">
                                    {{msg}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addProduct">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            products: [],
            categories: [],
            product: {
                product_name: '',
                category_id: 0,
                product_status: 0,
                unit_price: '',
                selling_price: '',
                product_image: ''
            },
            active: false,
            show: false,
            pagination: [],
            edit: false,
            errors: [],
            loading: false,
            file: '',
            page: 1,
            msg: null,
            imgURL: 'http://localhost/grocery/public/images/products/',
            imgBarcode: 'http://localhost/grocery/public/images/barcode/'
        }
    },
    created () {
        this.fetchProducts();
        this.fetchCategories();
    },
    computed: {
        validProductName: function() {
            return this.product.product_name.length <= 1;
        },
        validCategory: function() {
            return this.product.category_id == 0;
        },
        validUnitPrice: function() {
            return this.product.unit_price == 0 || this.product.unit_price == '';
        },
        validSellingPrice: function() {
            return this.product.selling_price == 0 || this.product.selling_price == '';
        },
        validStatus: function() {
            return this.product.product_status == 0;
        }
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        flush() {
            this.product = {
                product_name: '',
                category_id: 0,
                product_status: 0,
                unit_price: '',
                selling_price: '',
                product_image: ''
            }
        },
        createImage(product_id, index) {
            var app = this;
            const axios = require("axios");

            app.file = app.$refs.file[index].files[0];
            
            let splitFile = app.file.name.split('.');
            let fileName = product_id;
            let fileExt = splitFile[1];
            let newFile = fileName + '.' + fileExt;
            
            var formData = new FormData();

            formData.append('file', app.file, newFile);

            axios
                .post("/api/uploadProductImage/" , formData, {
                    header:{
                        'Content-Type' : 'multipart/form-data'
                    }
                })
                .then(function() {
                    window.location.reload(true)
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        fetchProducts(page) {
            var app = this;
            const axios = require("axios");
            
            app.loading = true;
            axios
                .get("/api/getAllProducts/?page=" + page)
                .then(function(response) {
                    app.products = response.data.data;
                    app.pagination = response.data.pagination;
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
                .get("/api/getAllCategories/?page=&status=active")
                .then(function(response) {
                    app.categories = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        searchProduct(name) {
            var app = this;
            const axios = require("axios");
            app.loading = true;
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchProduct/?product_name=" + name)
                    .then(function(response) {
                        app.products = response.data.data;
                        app.pagination = response.data.pagination;
                        app.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
                
        },
        addProduct() {
            var app = this;
            app.errors = [];

            if (app.validProductName) app.errors.product_name = "Please input Product Name.";
            if (app.validCategory) app.errors.category_id = "Please Select Category.";
            if (app.validUnitPrice) app.errors.unit_price = "Please input Unit Price greater 0.";
            if (app.validSellingPrice) app.errors.selling_price = "Please input Selling Price greater 0.";
            if (app.validStatus) app.errors.product_status = "Please Select Product Status.";

            if(
                !app.validProductName &&
                !app.validCategory &&
                !app.validUnitPrice &&
                !app.validSellingPrice &&
                !app.validStatus 
            ) {
                const axios = require("axios");
                if(app.edit == false) {
                    axios
                        .post("/api/addProducts/", app.product)
                        .then(function(response) {
                            app.msg = response.data.msg;

                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchProducts(app.page);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    axios
                        .put("/api/updateProduct/", app.product)
                        .then((response) => {
                            app.msg = response.data.msg;
                            app.edit = false;
                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchProducts(app.page);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        },
        editProduct(product) {
            let app = this;
            app.toggleModal();

            app.edit = true;

            app.product.id = product.id;
            app.product.product_name = product.product_name;
            app.product.unit_price = product.unit_price;
            app.product.selling_price = product.selling_price;
            app.product.product_status = (product.product_status == "active") ? 1 : 2;
            app.product.product_alarmlvl = product.product_alarmlvl;
            app.product.category_id = product.category_id;
            app.product.barcode = product.barcode;
        },
        deleteProduct(product_id, product_name) {
            if (confirm('Do you wat to delete ' + product_name)) {
                const axios = require("axios");
                axios
                    .put("/api/deleteProduct/", {'id' : product_id})
                    .then(() => {
                        this.fetchProducts(this.page);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                return false;
            }
        }
    }
}
</script>