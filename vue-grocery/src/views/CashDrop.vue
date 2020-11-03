<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="date" v-model="from_date" id="from_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="fetchRemittances($event.target.value, to_date)">
                                <label class="text-white" for="from_date">From Date</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="date" v-model="to_date" id="to_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date" @input="fetchRemittances(from_date, $event.target.value)">
                                <label class="text-white" for="to_date">To Date</label>
                            </div>
                        </div>
                        <div class="col-1 offset-5 mt-3">
                            <i class="fas fa-plus-circle fa-2x ml-4" title="Add Products" @click="toggleModal(), edit = false;" style="cursor: pointer;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Remitted By</th>
                                        <th>Total Expense</th>
                                        <th>Total Sales</th>
                                        <th>Remitted Amount</th>
                                        <th><b class="text-success">Over</b>/<b class="text-danger">Short</b></th>
                                        <th>Remit Date</th>
                                        <th>View</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-for="remittance in remittances" :key="remittance.remit_id">
                                    <tr :class="{'bg-warning': remittance.iscancel==1}">
                                        <td>{{ remittance.remit_id }}</td>
                                        <td>{{ remittance.employee_fn + " " + remittance.employee_sn }}</td>
                                        <td>&#8369; {{ remittance.expense_amount }}</td>
                                        <td>&#8369; {{ remittance.sales_amount }}</td>
                                        <td>&#8369; {{ remittance.remitted_amount }}</td>
                                        <td>&#8369; {{ (Number(remittance.remitted_amount) + Number(remittance.expense_amount) - Number(remittance.sales_amount)).toFixed(2) }}</td>
                                        <td>{{ remitDtFormat(remittance.created_at) }}</td>
                                        <td class="align-middle">
                                            <i class="far fa-file" style="cursor: pointer;" @click="openPortal(remittance.remit_id)"></i>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-ban ml-2" :title="'Click to Cancel Remit ID #' + remittance.remit_id" @click="cancelRemittance(remittance.remit_id)" style="cursor: pointer;" v-if="remittance.iscancel!=1"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row item-bottom pl-4 pr-4 pb-4">
                        <div class="col-4">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addProduct">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Cash Count</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tbody class="border-top-0" v-for="denom in denoms" :key="denom.id">
                                <tr>
                                    <td>&#8369; {{ denom.amount }}</td>
                                    <td class="pl-3 pr-3 pb-1"><input type="number" v-model="money[denom.id]" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0"></td>
                                    <td class="pl-3 pr-3"><input type="number" :value="(isNaN(money[denom.id] * denom.amount)) ? 0 : money[denom.id] * denom.amount" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0" disabled></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Cash Count :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Expense :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totExAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Cash Sale :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totSalesAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">To Remit :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ toRemit }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right"><b class="text-success">OVERAGE</b>  / <b class="text-danger">SHORTAGE</b>:</td>
                                    <td class="pl-2 pr-2" :class="{'text-danger':grandTotal<0, 'text-success':grandTotal>0}">&#8369; {{ grandTotal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addRemit">Save changes</button>
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
            denoms: [
                {
                    id: 0,
                    amount: 1000
                },
                {
                    id: 1,
                    amount: 500
                },
                {
                    id: 2,
                    amount: 200
                },
                {
                    id: 3,
                    amount: 100
                },
                {
                    id: 4,
                    amount: 50
                },
                {
                    id: 5,
                    amount: 20
                },
                {
                    id: 6,
                    amount: 10
                },
                {
                    id: 7,
                    amount: 5
                },
                {
                    id: 8,
                    amount: 1
                },
                {
                    id: 9,
                    amount: 0.25
                }
            ],
            money: [],
            remittances: [],
            remit_details : {
                remit_qty: 0,
                remit_amount: 0,
                remit_total: 0
            },
            expense_amount: [],
            sales_amount: [],
            from_date: new Date(),
            to_date: new Date(),
            active: false,
            show: false
        }
    },
    created () {
        this.from_date = moment().startOf('month').format("YYYY-MM-DD");
        this.to_date = moment().endOf('month').format("YYYY-MM-DD");
        this.fetchExpenseAmount();
        this.fetchSalesAmount();
        this.fetchRemittances(this.from_date, this.to_date);
    },
    methods: {
        remitDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        back() {
            this.$router.push("/");
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        openPortal(remit_id) {
            let routeData = this.$router.resolve({name: 'REMITTANCE', query: {remit_id: remit_id}});
            window.open(routeData.href, '_blank', "height=500,width=800");
        },
        fetchRemittances(from_date, to_date) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllRemittances/?fdate=" + from_date + "&tdate=" + to_date)
                .then(function(response) {
                    app.remittances = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchExpenseAmount() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllExpenseAmount/")
                .then(function(response) {
                    app.expense_amount = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSalesAmount() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSalesAmount/")
                .then(function(response) {
                    app.sales_amount = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addRemit() {
            var app = this;

            if (app.totAmount != 0 && app.totSalesAmount != 0) {
                var remit = {
                    cashier_id: app.$session.get("user_id"),
                    expense_amount: app.totExAmount,
                    sales_amount: app.totSalesAmount,
                    remitted_amount: app.totAmount
                }
                var d2 = [];
                app.denoms.forEach(element => {
                    d2.push({
                        remit_qty: app.money[element.id],
                        remit_amount: element.amount,
                        remit_total: element.amount * app.money[element.id],
                    });
                }); 
                var arr = new Array({'remittances': remit, 'remittance_details': d2.filter(function(el) { return el.remit_qty !== undefined || !isNaN(el.remit_total); })});
                const axios = require("axios");
                
                axios
                    .post("/api/addRemittance/", arr)
                    .then(function(response) {
                        console.log(response.data);
                        if(response.data) {
                            remit = [];
                            d2 = [];
                            app.money = [];
                            app.fetchExpenseAmount();
                            app.fetchSalesAmount();
                            app.fetchRemittances(app.from_date, app.to_date);
                            app.toggleModal();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        cancelRemittance(remit_id) {
            var app = this;
            const axios = require("axios");
            
            if (confirm("Do you want to cancel Remittance ID# " + remit_id)) {
                axios
                    .put("/api/cancelRemittance/", {'remit_id' : remit_id})
                    .then(function() {
                        app.fetchExpenseAmount();
                        app.fetchSalesAmount();
                        app.fetchRemittances(app.from_date, app.to_date);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    },
    computed: {
        totAmount: function() {
            var sum=0;
            this.denoms.forEach(element => {
                sum += (isNaN(element.amount * this.money[element.id])) ? 0 : element.amount * this.money[element.id]
            });
            return (sum).toFixed(2);
        },
        totExAmount: function() {
            var sum=0;
            this.expense_amount.forEach(element => {
                sum += parseFloat(element.total_amount)
            });
            return (sum).toFixed(2);
        },
        totSalesAmount: function() {
            var sum=0;
            this.sales_amount.forEach(element => {
                sum += parseFloat(element.total_amount)
            });
            return (sum).toFixed(2);
        },
        toRemit: function() {
            return (this.totSalesAmount - this.totExAmount).toFixed(2);
        },
        grandTotal: function() {
            return (Number(this.totAmount) + Number(this.totExAmount) - Number(this.totSalesAmount)).toFixed(2);
        }
    }
}
</script>