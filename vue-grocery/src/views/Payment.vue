<template>
    <div class="row">
        <div class="col-sm-3">
            <div class="card fullheight overflow-auto rounded-0">
                <div class="card-header bg-gradient">
                    <h5>Payment Setup</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="pay-by" class="ml-2 font-weight-light">Pay By:</label>
                            <select id="pay-by" v-model="payment.payment_typeid" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0">
                                <option value="1">Cash</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="customer" class="ml-2 font-weight-light">Customer:</label>
                            <select id="customer" v-model="payment.customer" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0">
                                <option value="1">Guest</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="comment" class="ml-2 font-weight-light">Comment:</label>
                            <textarea id="comment" class="form-control form-control-sm rounded-0" cols="30" rows="9"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary text-white btn-block item-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card fullheight overflow-auto rounded-0">
                <div class="card-header bg-gradient">
                    <h5>Order Summary</h5>
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
                    <button class="btn btn-info btn-block item-bottom btn-lg rounded-0" disabled>.</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card fullheight overflow-auto">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" v-model="total" class="form-control form-control-lg" disabled>
                            <div class="row no-gutters">
                                <div class="col-sm-3">
                                    <button @click="key(20)" class="btn btn-lg btn-info border btn-block p-4 px-0">&#8369; 20</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(100)" class="btn btn-lg btn-info border btn-block p-4 px-0">&#8369; 100</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(500)" class="btn btn-lg btn-info border btn-block p-4 px-0">&#8369; 500</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(1000)" class="btn btn-lg btn-info border btn-block p-4">&#8369; 1000</button>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-sm-3">
                                    <button @click="key(7)" class="btn btn-lg btn-dark border btn-block p-5 px-0">7</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(8)" class="btn btn-lg btn-dark border btn-block p-5 px-0">8</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(9)" class="btn btn-lg btn-dark border btn-block p-5 px-0">9</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="clear(total)" class="btn btn-lg btn-danger border btn-block p-5 px-0">&larr;</button>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-sm-3">
                                    <button @click="key(4)" class="btn btn-lg btn-dark border btn-block p-5 px-0">4</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(5)" class="btn btn-lg btn-dark border btn-block p-5 px-0">5</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(6)" class="btn btn-lg btn-dark border btn-block p-5 px-0">6</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="clearAll" class="btn btn-lg btn-danger border btn-block p-5 px-0">C</button>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-sm-3">
                                    <button @click="key(1)" class="btn btn-lg btn-dark border btn-block p-5 px-0">1</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(2)" class="btn btn-lg btn-dark border btn-block p-5 px-0">2</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(3)" class="btn btn-lg btn-dark border btn-block p-5 px-0">3</button>
                                </div>
                                <div class="col-sm-3">
                                    <button @click="key(0)" class="btn btn-lg btn-dark border btn-block p-5 px-0">0</button>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-sm-12"><button value="0" class="btn btn-lg btn-success border btn-block p-4 px-0">Tender</button></div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                                <input type="text" :min="1" :max="order_disc.product_qty" v-model="order_disc.product_qty" @input="minDiscQty($event, index)" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" style="width:40px;">
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
export default {
    data () {
        return {
            orders: [],
            order_discs: [],
            disc: 20,
            payment: {
                payment_typeid : 1,
                customer : 1,
                discount : 0
            },
            total: 0,
            show: false,
            active: false
        }
    },
    created () {
        this.fetchOrderProducts();
    },
    methods: {
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
                return app.order_discs[selectedIndex].product_qty = 1;
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
                return app.orders[selectedIndex].product_qty = 1;
            } else if (inputValue > maxValue) {
                return app.orders[selectedIndex].product_qty = maxValue;
            } else {
                return app.orders[selectedIndex].product_qty = inputValue;
            }
        },
        back() {
            this.$router.push("/terminal");
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        key(num) {
            if(num == 0) {
                return this.total = Number(this.total) + Number(num) + '0';
            } else {
                if (num >= 20) {
                    return this.total += Number(num);
                } else {
                    return this.total = Number(this.total) + String(num);
                }
            }
        },
        clear(num){
            if (num > 1) {
                return this.total = String(num).substr(0, String(num).length - 1);
            } else {
                return this.total = Number(0);
            } 
        },
        clearAll(){
            return this.total = 0;
        },
        fetchOrderProducts() {
            var app = this;
            const axios = require("axios");
            axios
                .get("/api/getAllOrderProducts.php")
                .then(function(response) {
                    app.orders = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
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
                .then((res) => {
                    console.log(res.data);
                    app.fetchOrderProducts();
                    app.toggleModal();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
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