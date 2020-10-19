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
                            <label for="disc" class="ml-2 font-weight-light">Discount:</label>
                            <input type="number" v-model="payment.discount" id="disc" :min="0" :max="20" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0">
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
                    <div class="row overflow-auto" style="height: 65vh;">
                        <div class="col-12">
                            <table class="table table-striped">
                                <tbody>
                                    <tr v-for="(order, index) in orders" :key="order.id" :value="order.id">
                                        <td class="pt-2 pb-2 align-middle" :title="order.product_name">{{order.product_name}}</td>
                                        <td class="pt-2 pb-2 align-middle"><input type="number" :min="1" v-model="order.product_qty" @input="minInput($event, index), updateOrderProduct(order.id, order.product_qty, order.product_id)" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" style="width:70px;"></td>
                                        <td class="pt-2 pb-2 align-middle">&#8369; {{order.total_amount}}</td>
                                        <td class="pt-2 pb-2 align-middle" style="cursor: pointer;" :title="'Delete ' + order.product_name" @click="deleteOrderProduct(order.id)"><i class="fas fa-times-circle"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-info btn-block item-bottom btn-lg rounded-0" style="cursor: context-menu;"><span class="float-left mr-3">Total: </span><span class="float-right mr-3">{{(orderTotal == 0) ? '' : "&#8369; " + orderTotal }}</span></button>
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
                                <div class="col-sm-6"><button value="0" class="btn btn-lg btn-primary border btn-block text-white p-4 px-0">Hold</button></div>
                                <div class="col-sm-6"><button value="0" class="btn btn-lg btn-success border btn-block p-4 px-0">Tender</button></div> 
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
            payment: {
                payment_typeid : 1,
                customer : 1,
                discount : 0
            },
            total: 0,
            loading: false
        }
    },
    created () {
        this.fetchOrderProducts();
    },
    methods: {
        minInput(event, selectedIndex) {
            var app = this;
            const inputValue = parseInt(event.target.value);
            const minValue = parseInt(event.target.min);
            
            if (inputValue < minValue || Number.isNaN(inputValue)) {
                return app.orders[selectedIndex].product_qty = 1;
            } else {
                return app.orders[selectedIndex].product_qty = inputValue;
            }
        },
        back() {
            this.$router.push("/terminal");
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
                .put("/api/updateOrderProduct.php" , {"id": id, "qty": qty, "product_id": product_id})
                .then(() => {
                    app.fetchOrderProducts();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteOrderProduct(id) {
            var app = this;
            const axios = require("axios");
            axios
                .delete("/api/deleteOrderProduct.php?id=" + id)
                .then(() => {
                    app.fetchOrderProducts();
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    computed: {
        orderTotal: function() {
            var sum=0;
            this.orders.forEach(element => {
                sum += parseFloat(element.total_amount)
            });
            return sum.toFixed(2);
        }
    }
}
</script>