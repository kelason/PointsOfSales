<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="from_date" id="from_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="fetchSpoilages(from_date, to_date)">
                                <label class="text-white" for="from_date">From Date</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="datetime-local" v-model="to_date" id="to_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="fetchSpoilages(from_date, to_date)">
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
                                        <th>Cashier</th>
                                        <th>Note</th>
                                        <th>Date</th>
                                        <th>View</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="spoilage in spoilages" :key="spoilage.id" :class="{'bg-warning' : spoilage.iscancel==1}">
                                        <td class="align-middle">{{ spoilage.id }}</td>
                                        <td class="align-middle">{{ spoilage.employee_fn }} {{ spoilage.employee_sn }}</td>
                                        <td class="align-middle">{{ spoilage.spoilage_note }}</td>
                                        <td class="align-middle">{{ spoilageDtFormat(spoilage.created_at) }}</td>
                                        <td class="align-middle">
                                            <i class="far fa-file" style="cursor: pointer;" @click="openPortal(spoilage.id)"></i>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-ban ml-2" :title="'Click to Cancel ID# ' + spoilage.id" style="cursor: pointer;" @click="cancelSpoilage(spoilage.id)" v-if="spoilage.iscancel!=1"></i>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addSpoilage">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Spoilage</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-label-group">
                                    <input type="datetime-local" v-model="spoilage.created_at" id="created_at" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                    <label for="created_at">Date</label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="comment" class="ml-2 font-weight-light">Comment:</label>
                                <textarea id="comment" v-model="spoilage.spoilage_note" class="form-control form-control-sm rounded-0" cols="30" rows="7" placeholder="Optional"></textarea>
                            </div>
                            <hr>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="spoilage_product.search_product" id="search_product" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product" @input="fetchProducts($event.target.value)">
                                    <label for="search_product">Search Product</label>
                                </div>
                            </div>
                            <div class="col-6 p-2">
                                <select id="product_id" v-model="spoilage_product.product_id" @change="fetchProductById()" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Product</option>
                                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.product_name + "  (" + product.stock_qty + ")"}}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="spoilage_product.spoilage_qty" id="spoilage_qty" min="1" :max="max_stock" @input="maxInput" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Quantity">
                                    <label for="spoilage_qty">Quantity</label>
                                </div>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dup, index) in dups" :key="dup.product_id">
                                            <td>{{ dups[index].product_name }}</td>
                                            <td>{{ dups[index].spoilage_qty }}</td>
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
                                            <td class="float-right">{{ (spoilageTotal == 0) ? '' : 'Total: ' }}</td>
                                            <td>{{ (spoilageTotal == 0) ? '' : '&#8369; ' + spoilageTotal }}</td>
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
                        <button type="button" class="btn btn-primary" @click="addSpoilage">Save changes</button>
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
            spoilages: [],
            products: [],
            suppliers: [],
            queue_spoilage: [],
            dups: [],
            max_stock: 0,
            spoilage: {
                cashier_id: this.$session.get('user_id'),
                created_at: new Date(),
                spoilage_note: ''
            },
            from_date: new Date(),
            to_date: new Date(),
            spoilage_product: {
                product_id: 0,
                product_name: '',
                spoilage_qty: 1,
                unit_price: 0
            },
            page: 1
        }
    },
    created() {
        this.spoilage.created_at = moment().format("YYYY-MM-DDThh:mm");
        this.from_date = moment().startOf('month').format("YYYY-MM-DDThh:mm");
        this.to_date = moment().endOf('month').format("YYYY-MM-DDTkk:mm");
        this.fetchProducts();
        this.fetchSpoilages(this.from_date, this.to_date);
    },
    computed: {
        spoilageTotal: function() {
            var sum=0;
            this.dups.forEach(element => {
                sum += parseFloat(element.spoilage_qty * element.unit_price)
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
        spoilageDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        openPortal(spoilage_id) {
            let routeData = this.$router.resolve({name: 'PSPOILAGE', query: {spoilage_id: spoilage_id}});
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
        maxInput(event) {
            var app = this;
            const inputValue = parseInt(event.target.value);
            const minValue = parseInt(event.target.min);
            const maxValue = parseInt(event.target.max);
            
            if (inputValue < minValue || Number.isNaN(inputValue)) {
                return app.spoilage_product.spoilage_qty = 0;
            } else if (inputValue > maxValue) {
                return app.spoilage_product.spoilage_qty = maxValue;
            } else {
                return app.spoilage_product.spoilage_qty = inputValue;
            }
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
                    .get("/api/searchProductInventories/?product_name=" + name)
                    .then(function(response) {
                        const responseData = response.data.data;
                        app.products = responseData.filter(item => item.stock_qty != 0);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
        },
        fetchSpoilages(from_date, to_date) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSpoilages/?from_date=" + from_date + "&to_date=" + to_date)
                .then(function(response) {
                    app.spoilages = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchProductById() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getProductInventoriesById/?product_id=" + app.spoilage_product.product_id)
                .then(function(response) {
                    const prod = response.data.data;
                    
                    prod.forEach(element => {
                        app.spoilage_product.product_name = element.product_name;
                        app.max_stock = element.stock_qty;
                    });
                   
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addSpoilage() {
            var app = this;
            var arr = new Array({'spoilage': app.spoilage, 'spoilage_product': app.dups});
            const axios = require("axios");

            axios
                .post("/api/addSpoilage/", arr)
                .then(function(response) {
                    console.log(response.data);
                    if (response.data) {
                        app.spoilage = {
                            cashier_id: app.$session.get('user_id'),
                            created_at: moment(new Date()).format("YYYY-MM-DDThh:mm"),
                            spoilage_note: ''
                        },
                        app.spoilage_product = {
                            product_id: 0,
                            product_name: '',
                            spoilage_qty: 1,
                            unit_price: 0
                        },
                        app.dups = [];
                        app.toggleModal();
                        app.fetchProducts();
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cancelSpoilage(id) {
            var app = this;
            const axios = require("axios");

            if (confirm("Do you want to cancel spoilage ID# " + id)) {
                axios
                    .put("/api/cancelSpoilageById/", {'spoilage_id' : id})
                    .then(function() {
                        app.fetchProducts();
                        app.fetchSpoilages(app.from_date, app.to_date);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
            
        },
        addQueue() {
            var app = this;
            if (app.spoilage_product.product_id != 0) app.queue_spoilage.push({...this.spoilage_product});
            
            app.queue_spoilage.filter(function(el, i) {
                if (i === app.queue_spoilage.length - 1 && app.dups.map(function(e) {return e.product_id}).indexOf(el.product_id) == -1) {
                    app.dups.push({
                        product_id: el.product_id,
                        product_name: el.product_name,
                        spoilage_qty: el.spoilage_qty,
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