<template>
    <div class="container">
        <div class="row mx-auto">
            <div class="col-sm-12">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
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
    </div>
</template>
<script>
export default {
    data () {
        return {
            expense_details: [],
        }
    },
    created() {
        this.fetchExpenseDetails();
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
        fetchExpenseDetails() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllExpenseDetailsById/?expense_id=" + app.$route.query.expense_id)
                .then(function(response) {
                    console.log(response.data);
                    app.expense_details = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>