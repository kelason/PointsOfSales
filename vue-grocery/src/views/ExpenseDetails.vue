<template>
    <div class="container" style="background-color: #ffffff">
        <div class="row mx-auto">
            <div class="col-sm-12">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        <img :src="imgURL + 'cancelled.png'" style="position:absolute; width: 600px; z-index: 10;" v-if="expense.iscancel == 1">
        <div class="row mx-auto">
            <div class="col-sm-6"><strong>Remitted By:</strong> {{ expense.employee_fn + " " + expense.employee_sn }}</div>
            <div class="col-sm-6"><strong>Date:</strong> {{ expenseDtFormat(expense.created_at) }}</div>
        </div>
        <br><br>
        <div class="row mx-auto">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Particulars</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-bottom" v-for="expense_detail in expense_details" :key="expense_detail.id">
                            <td>{{ expense_detail.particulars_name }}</td>
                            <td>&#8369; {{ expense_detail.expense_amount }}</td>
                        </tr>
                        <tr>
                            <td class="text-right font-weight-bold pr-3">Total:</td>
                            <td>&#8369; {{totalExpenses}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mx-auto mt-4">
            <div class="col-sm-4 signature"></div>
        </div>
        <div class="row mx-auto">
            <div class="col-sm-4">
                Checked By:
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    data () {
        return {
            expense: [],
            expense_details: [],
            imgURL: '/grocery/public/images/'
        }
    },
    created() {
        this.fetchExpenseDetails();
        this.fetchExpenses();
    },
    computed: {
        totalExpenses: function() {
            var sum=0;
            this.expense_details.forEach(element => {
                sum += parseFloat(element.expense_amount)
            });
            return sum.toFixed(2);
        }
    },
    methods: {
        expenseDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchExpenses() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getExpenseById/?expense_id=" + app.$route.query.expense_id)
                .then(function(response) {
                    app.expense = response.data[0];
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchExpenseDetails() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllExpenseDetailsById/?expense_id=" + app.$route.query.expense_id)
                .then(function(response) {
                    app.expense_details = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>