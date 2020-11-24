<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="from_date" id="from_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="searchPurchaseByDate">
                                <label class="text-white" for="from_date">From Date</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="to_date" id="to_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="searchPurchaseByDate">
                                <label class="text-white" for="to_date">To Date</label>
                            </div>
                        </div>
                        <div class="col-1 offset-5 mt-3">
                            <i class="fas fa-plus-circle fa-2x" title="Add Purchase" @click="toggleModal(), edit = false;" style="cursor: pointer;"></i>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier</th>
                                        <th>Cashier</th>
                                        <th>Delivery Reciept#</th>
                                        <th>Note</th>
                                        <th>Date</th>
                                        <th>Purchase Product</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="purchase in purchases" :key="purchase.id" :class="{'bg-warning' : purchase.iscancel==1}">
                                        <td class="align-middle">{{ purchase.id }}</td>
                                        <td class="align-middle">{{ purchase.supplier_name }}</td>
                                        <td class="align-middle">{{ purchase.employee_fn }} {{ purchase.employee_sn }}</td>
                                        <td class="align-middle">{{ purchase.drnum }}</td>
                                        <td class="align-middle">{{ purchase.purchase_note }}</td>
                                        <td class="align-middle">{{ purchaseDtFormat(purchase.created_at) }}</td>
                                        <td class="align-middle">
                                            <i class="far fa-file" style="cursor: pointer;" @click="openPortal(purchase.id)"></i>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-ban ml-2" :title="'Click to Cancel ' + purchase.id" style="cursor: pointer;" @click="cancelPurchase(purchase.id)" v-if="purchase.iscancel!=1"></i>
                                            <i class="fas fa-ban ml-2" v-else></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchPurchases(page = page - 1)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchPurchases(page = page + 1)">
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
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addPurchase">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Purchase</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="datetime-local" v-model="purchase.created_at" id="created_at" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                    <label for="created_at">Date</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.created_at">{{ errors.created_at }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="purchase.drnum" id="drnum" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Delivery Reciept#">
                                    <label for="drnum">Delivery Reciept#</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.drnum">{{ errors.drnum }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="supplier_id" v-model="purchase.supplier_id" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Supplier</option>
                                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.supplier_name }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_id">{{ errors.supplier_id }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="purchase.purchase_note" id="purchase_note" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Note (Optional)">
                                    <label for="purchase_note">Note (Optional)</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.note">{{ errors.note }}</small>
                            </div>
                            <hr>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="purchase_product.search_product" id="search_product" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product" @input="fetchProducts($event.target.value)">
                                    <label for="search_product">Search Product</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.search_product">{{ errors.search_product }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="product_id" v-model="purchase_product.product_id" @change="fetchProductById()" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Product</option>
                                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.product_name }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.product_id">{{ errors.product_id }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="purchase_product.purchase_qty" id="purchase_qty" min="1" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Quantity">
                                    <label for="purchase_qty">Quantity</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.purchase_qty">{{ errors.purchase_qty }}</small>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-info btn-block item-bottom btn-sm rounded-0 mb-3" @click="addQueue"><i class="fas fa-plus"></i> ADD</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dup, index) in dups" :key="dup.product_id">
                                            <td>{{ dups[index].product_name }}</td>
                                            <td>{{ dups[index].purchase_qty }}</td>
                                            <td>&#8369; {{dups[index].unit_price }}</td>
                                            <td>&#8369; {{parseFloat(dups[index].purchase_qty * dups[index].unit_price).toFixed(2)}}</td>
                                            <td>
                                                <i class="fa fa-trash ml-2" :title="'Click to Delete ' + dups[index].product_name" style="cursor: pointer;" @click="deleteQueue(index)"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <hr>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="float-right">{{ (purchaseTotal == 0) ? '' : 'Total: ' }}</td>
                                            <td>{{ (purchaseTotal == 0) ? '' : '&#8369; ' + purchaseTotal }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                        <button type="button" class="btn btn-primary" @click="addPurchase">Save changes</button>
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
            msg: '',
            show: false,
            active: false,
            edit: false,
            errors: [],
            pagination: [],
            purchases: [],
            products: [],
            suppliers: [],
            queue_purchase: [],
            dups: [],
            purchase: {
                supplier_id: 0,
                drnum: '',
                cashier_id: this.$session.get('user_id'),
                created_at: new Date(),
                purchase_note: ''
            },
            from_date: new Date(),
            to_date: new Date(),
            purchase_product: {
                product_id: 0,
                product_name: '',
                purchase_qty: 1,
                unit_price: 0
            },
            page: 1
        }
    },
    created() {
        this.purchase.created_at = moment().format("YYYY-MM-DDTkk:mm");
        this.from_date = moment().startOf('month').format("YYYY-MM-DDThh:mm");
        this.to_date = moment().endOf('month').format("YYYY-MM-DDTkk:mm");
        this.searchPurchaseByDate();
        this.fetchProducts();
        this.fetchSuppliers();
        this.fetchPurchases();
    },
    computed: {
        purchaseTotal: function() {
            var sum=0;
            this.dups.forEach(element => {
                sum += parseFloat(element.purchase_qty * element.unit_price)
            });
            return sum.toFixed(2);
        },
        validDr: function() {
            return this.purchase.drnum.length <= 1;
        },
        validSupplier: function() {
            return this.purchase.supplier_id == 0;
        },
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        purchaseDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        openPortal(purchase_id) {
            let routeData = this.$router.resolve({name: 'PURCHASE PRODUCTS', query: {purchase_id: purchase_id}});
            window.open(routeData.href, '_blank', "height=500,width=800");
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        fetchProducts(name='') {
            var app = this;
            const axios = require("axios");
            
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchProduct/?product_name=" + name + "&limit=all")
                    .then(function(response) {
                        app.products = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
        },
        fetchSuppliers() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSuppliers/")
                .then(function(response) {
                    app.suppliers = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchPurchases(page) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllPurchases/?page=" + page)
                .then(function(response) {
                    app.purchases = response.data.data;
                    app.pagination = response.data.pagination;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchProductById() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllProductsById/?product_id=" + app.purchase_product.product_id)
                .then(function(response) {
                    const prod = response.data.data;
                    
                    prod.forEach(element => {
                        app.purchase_product.product_name = element.product_name;
                        app.purchase_product.unit_price = element.unit_price;
                    });
                   
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        searchPurchaseByDate() {
            var app = this;
            const axios = require("axios");
            
            var fromDate = moment(app.from_date).format('YYYY-MM-DD HH:mm:ss');
            var toDate = moment(app.to_date).format('YYYY-MM-DD HH:mm:ss');

            axios
                .get("/api/getPurchaseByDate/?from_date=" + fromDate + "&to_date=" + toDate)
                .then(function(response) {
                    app.purchases = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addPurchase() {
            var app = this;
            app.errors = [];

            if (app.validSupplier) app.errors.supplier_id = "Please Select Supplier.";
            if (app.validDr) app.errors.drnum = "Please Input Delivery Reciept #.";

            if(
                !app.validSupplier &&
                !app.validDr
            ) {
                var arr = new Array({'purchase': this.purchase, 'purchase_product': this.dups});
                const axios = require("axios");

                axios
                    .post("/api/addPurchase/", arr)
                    .then(function(response) {
                        if (response.data) {
                            app.purchase = {
                                supplier_id: 0,
                                drnum: '',
                                cashier_id: app.$session.get('user_id'),
                                created_at: moment().format("YYYY-MM-DDTkk:mm"),
                                purchase_note: ''
                            }
                            app.purchase_product = {
                                product_id: 0,
                                search_product: '',
                                product_name: '',
                                purchase_qty: 1,
                                unit_price: 0
                            }
                            app.dups = [];
                            app.toggleModal();
                            app.fetchPurchases(app.page);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        cancelPurchase(id) {
            var app = this;
            const axios = require("axios");

            if (confirm("Do you want to cancel purchase ID# " + id)) {
                axios
                    .get("/api/cancelPurchaseById/?purchase_id=" + id)
                    .then(function() {
                        app.fetchPurchases();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
            
        },
        addQueue() {
            var app = this;
            if (app.purchase_product.product_id != 0) app.queue_purchase.push({...this.purchase_product});
            
            app.queue_purchase.filter(function(el, i) {
                if (i === app.queue_purchase.length - 1 && app.dups.map(function(e) {return e.product_id}).indexOf(el.product_id) == -1) {
                    app.dups.push({
                        product_id: el.product_id,
                        product_name: el.product_name,
                        purchase_qty: el.purchase_qty,
                        unit_price: el.unit_price,
                    });
                }
            });
        },
        deleteQueue(index) {
            this.dups.splice(index, 1);
        }
    }
}
</script>