<template>
    <div class="container" style="background-color: #ffffff">
        <div class="row mx-auto">
            <div class="col-sm-12">
                <h5>Cabanatuan City, Nueva Ecija</h5>
            </div>
        </div>
        
        <div class="row mx-auto" v-for="remittance in remittances" :key="remittance.id">
            <img :src="imgURL + 'cancelled.png'" style="position:absolute; width: 600px; z-index: 10;" v-if="remittance.iscancel == 1">
            <div class="col-sm-6"><strong>Remitted By:</strong> {{ remittance.employee_fn + " " + remittance.employee_sn }}</div>
            <div class="col-sm-6"><strong>Date:</strong> {{ remitDtFormat(remittance.created_at) }}</div>
        </div>
        <br><br>
        <div class="row mx-auto">
            <div class="col-sm-12">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-bottom" v-for="remit_detail in remit_details" :key="remit_detail.id">
                            <td>{{ remit_detail.remit_qty }}</td>
                            <td>&#8369; {{ remit_detail.remit_amount }}</td>
                            <td>&#8369; {{ remit_detail.remit_total }}</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Total:</td>
                            <td>&#8369; {{ totalAmount }}</td>
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
            remittances: [],
            remit_details : [],
            imgURL: 'http://localhost/grocery/public/images/'
        }
    },
    created () {
        this.fetchRemittances();
        this.fetchRemittanceDetails();
    },
    computed: {
        totalAmount: function() {
            var sum=0;
            this.remit_details.forEach(element => {
                sum += parseFloat(element.remit_total)
            });
            return (sum).toFixed(2);
        }
    },
    methods: {
        remitDtFormat(dt) {
            return moment(dt).format("MMMM DD, YYYY hh:mm:ss A");
        },
        fetchRemittances() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllRemittancesById/?remit_id=" + app.$route.query.remit_id)
                .then(function(response) {
                    app.remittances = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchRemittanceDetails() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllRemittanceDetailsById/?remit_id=" + app.$route.query.remit_id)
                .then(function(response) {
                    app.remit_details = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>