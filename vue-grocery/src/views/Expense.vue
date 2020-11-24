<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="date" v-model="from_date" id="from_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                <label class="text-white" for="from_date">From Date</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-label-group">
                                <input type="date" v-model="to_date" id="to_date" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
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
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cashier Name</th>
                                        <th>Total Expense</th>
                                        <th>Note</th>
                                        <th>Date</th>
                                        <th>View</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-for="ex in expenses" :key="ex.ex_id">
                                    <tr :class="{'bg-warning' : ex.iscancel==1}">
                                        <td>{{ ex.ex_id }}</td>
                                        <td>{{ ex.employee_fn + " " + ex.employee_sn }}</td>
                                        <td>&#8369; {{ ex.total_amount }}</td>
                                        <td>{{ ex.expense_note }}</td>
                                        <td>{{ expenseDtFormat(ex.created_at) }}</td>
                                        <td class="align-middle">
                                            <i class="far fa-file" style="cursor: pointer;" @click="openPortal(ex.ex_id)"></i>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-ban ml-2" :title="'Click to Cancel Expense ID #' + ex.ex_id" style="cursor: pointer;" @click="cancelExpense(ex.ex_id)" v-if="ex.iscancel!=1"></i>
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
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addPurchase">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-label-group">
                                    <input type="datetime-local" v-model="expense.created_at" id="created_at" class="form-control form-control-sm mb-3 bg-transparent rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Date">
                                    <label for="created_at">Expense Date</label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="comment" class="ml-2 font-weight-light">Comment:</label>
                                <textarea id="comment" v-model="expense.expense_note" class="form-control form-control-sm rounded-0" cols="30" rows="7" placeholder="Optional"></textarea>
                            </div>
                            <hr>
                            <div class="col-sm-6 mt-2">
                                <select id="particulars_id" v-model="expense_details.particulars_id" @change="fetchParticularsById()" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Particulars</option>
                                    <option v-for="particular in particulars" :key="particular.id" :value="particular.id">{{ particular.particulars_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-label-group">
                                    <input type="number" v-model="expense_details.expense_amount" id="expense_amount" min="0" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Expense Amount">
                                    <label for="expense_amount">Expense Amount</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-info btn-block btn-sm rounded-0 mb-3" @click="addQueue"><i class="fas fa-plus"></i> ADD</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th>Particulars Name</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(dup, index) in dups" :key="dup.product_id">
                                            <td>{{ dups[index].particulars_name }}</td>
                                            <td>&#8369; {{parseFloat(dups[index].expense_amount).toFixed(2)}}</td>
                                            <td>
                                                <i class="fa fa-trash ml-2" :title="'Click to Delete ' + dups[index].particulars_name" style="cursor: pointer;" @click="deleteQueue(index)"></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <hr>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="float-right">{{ (expenseTotal == 0) ? '' : 'Total: ' }}</td>
                                            <td>{{ (expenseTotal == 0) ? '' : '&#8369; ' + expenseTotal }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addExpense">Save changes</button>
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
            suppliers: [],
            dups: [],
            particulars: [],
            queue_expense: [],
            expenses:[],
            expense: {
                total_amount: 0,
                cashier_id: this.$session.get('user_id'),
                created_at: new Date(),
                expense_note: ''
            },
            from_date: new Date(),
            to_date: new Date(),
            expense_details: {
                particulars_id: 0,
                expense_amount: 0
            },
            page: 1
        }
    },
    created() {
        this.expense.created_at = moment().format("YYYY-MM-DDTkk:mm");
        this.from_date = moment().startOf('month').format("YYYY-MM-DD");
        this.to_date = moment().endOf('month').format("YYYY-MM-DD");
        this.fetchParticulars();
        this.fetchExpenses();
    },
    computed: {
        expenseTotal: function() {
            var sum=0;
            this.dups.forEach(element => {
                sum += parseFloat(element.expense_amount)
            });
            return sum.toFixed(2);
        },
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        expenseDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        openPortal(expense_id) {
            let routeData = this.$router.resolve({name: 'EXPENSE DETAILS', query: {expense_id: expense_id}});
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
        fetchParticulars() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllParticulars/")
                .then(function(response) {
                    app.particulars = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchExpenses() {
            var app = this;
            const axios = require("axios");

            axios
                .get("/api/getAllExpenses/?from_date=" + this.from_date + "&to_date=" + this.to_date)
                .then(function(response) {
                    app.expenses = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchParticularsById() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllParticularsById/?particulars_id=" + app.expense_details.particulars_id)
                .then(function(response) {
                    const prod = response.data.data;
                    
                    prod.forEach(element => {
                        app.expense_details.particulars_name = element.particulars_name;
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        addExpense() {
            var app = this;

            app.expense = {
                total_amount: app.expenseTotal,
                cashier_id: app.$session.get('user_id'),
                created_at: new Date(),
                expense_note: ''
            }
            
            var arr = new Array({'expense': app.expense, 'expense_details': app.dups});
            const axios = require("axios");

            axios
                .post("/api/addExpense/", arr)
                .then(function(response) {
                    if (response.data) {
                        app.expense = {
                            total_amount: app.expenseTotal,
                            cashier_id: app.$session.get('user_id'),
                            created_at: moment(new Date()).format("YYYY-MM-DDTkk:mm"),
                            expense_note: ''
                        }
                        app.expense_details = {
                            particulars_id: 0,
                            expense_amount: 0
                        }
                        app.dups = [];
                        app.toggleModal();
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        cancelExpense(expense_id) {
            var app = this;
            const axios = require("axios");
            
            if (confirm('Are you sure to delete Expense ID#' + expense_id)) {
                axios
                    .get("/api/cancelExpensesById/?expense_id=" + expense_id)
                    .then(function() {
                        app.fetchExpenses();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        addQueue() {
            var app = this;
            if (app.expense_details.particulars_id > 0) app.queue_expense.push({...app.expense_details});
            
            app.queue_expense.filter(function(el, i) {
                if (i === app.queue_expense.length - 1 && app.dups.map(function(e) {return e.particulars_id}).indexOf(el.particulars_id) == -1) {
                    app.dups.push({
                        particulars_id: el.particulars_id,
                        particulars_name: el.particulars_name,
                        expense_amount: el.expense_amount
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