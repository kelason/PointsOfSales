<template>
    <div class="row">
        <div class="col-sm-2">
            <div class="card fullheight rounded-0" style="overflow: auto;">
                <div class="card-header bg-gradient text-white rounded-0 p-3">
                    <h5>Menus</h5> 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(menu, index) in menus" :value="menu.id" :key="menu.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{'active' : (selectedMenu !== null) ? menu.id == selectedMenu : index===0}" @click="selectedMenu = menu.id, headerName=menu.menu_name, fetchSales(z_employee_id, z_from_date, z_to_date), fetchSalesSingle(x_employee_id, x_from_date), fetchSalesProduct(ps_select, ps_from_date, ps_to_date), fetchSalesRanking(rank_from_date, rank_to_date), fetchEmployees()">{{ menu.menu_name }}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary text-white btn-block item-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="card fullheight rounded-0" style="overflow: auto;">
                <div class="card-header bg-gradient text-white rounded-0 pt-0 pb-0">
                    <div class="row" v-if="selectedMenu === null || selectedMenu == 1">
                        <div class="col-sm-2 mt-3"><h5>{{ headerName }}</h5> </div>
                        <div class="col-sm-2 mt-3">
                            <select v-model="payment_id" @change="fetchSalesInvoice($event.target.value, customer_id, inv_from_date, inv_to_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" v-for="payment_type in payment_types" :key="payment_type.id" :value="payment_type.id">{{ payment_type.payment_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-2 mt-3">
                            <select v-model="customer_id" @change="fetchSalesInvoice(payment_id, $event.target.value, inv_from_date, inv_to_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.customer_name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" @input="fetchSalesInvoice(payment_id, customer_id, $event.target.value, inv_to_date)" v-model="inv_from_date" id="barfdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bardt" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="datetime-local" @input="fetchSalesInvoice(payment_id, customer_id, inv_from_date, $event.target.value)" v-model="inv_to_date" id="bartdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bartdt" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 2">
                        <div class="col-sm-6 mt-3"><h5>{{ headerName }}</h5> </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSalesRanking($event.target.value, rank_to_date)" v-model="rank_from_date" id="barfdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bardt" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSalesRanking(rank_from_date, $event.target.value)" v-model="rank_to_date" id="bartdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bartdt" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 3">
                        <div class="col-sm-3 mt-3"><h5>{{ headerName }}</h5> </div>
                        <div class="col-sm-3 mt-3">
                            <select v-model="ps_select" @change="fetchSalesProduct($event.target.value, ps_from_date, ps_to_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="1">By Products</option>
                                <option class="text-dark" value="2">By Categories</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSalesProduct(ps_select, $event.target.value, ps_to_date)" v-model="ps_from_date" id="barfdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bardt" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSalesProduct(ps_select, ps_from_date, $event.target.value)" v-model="ps_to_date" id="bartdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="bartdt" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 4">
                        <div class="col-sm-6 mt-3"><h5>{{ headerName }}</h5> </div>
                        <div class="col-sm-3">
                            <select v-model="x_employee_id" @change="fetchSalesSingle($event.target.value, x_from_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent mt-3 border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="0">All Employees</option>
                                <option class="text-dark" v-for="employee in x_employees" :key="employee.id" :value="employee.id">{{ employee.employee_fn + ' ' + employee.employee_sn }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSalesSingle(x_employee_id, $event.target.value)" v-model="x_from_date" id="piefdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="pirfdt" class="text-white">From Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 5">
                        <div class="col-sm-3 mt-3"><h5>{{ headerName }}</h5> </div>
                        <div class="col-sm-3">
                            <select v-model="z_employee_id" @change="fetchSales($event.target.value, z_from_date, z_to_date)" class="form-control form-control-sm rounded-0 text-white bg-transparent mt-3 border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="0">All Employees</option>
                                <option class="text-dark" v-for="employee in z_employees" :key="employee.id" :value="employee.id">{{ employee.employee_fn + ' ' + employee.employee_sn }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSales(z_employee_id, $event.target.value, z_to_date)" v-model="z_from_date" id="piefdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="pirfdt" class="text-white">From Date</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-label-group">
                                <input type="date" @input="fetchSales(z_employee_id, z_from_date, $event.target.value)" v-model="z_to_date" id="pietdt" class="form-control form-control-sm text-white bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Date">
                                <label for="pietdt" class="text-white">To Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" v-if="selectedMenu === null || selectedMenu == 1">
                        <div class="col-12">
                            <table class="table table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Payment Type</th>
                                        <th>Customer</th>
                                        <th>Cashier</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Date Time</th>
                                        <th>Void</th>
                                    </tr>
                                </thead>
                                <tbody v-for="sales_invoice in sales_invoices" :key="sales_invoice.id">
                                    <tr>
                                        <td>{{ sales_invoice.id }}</td>
                                        <td>{{ sales_invoice.payment_name }}</td>
                                        <td>{{ sales_invoice.customer_name }}</td>
                                        <td>{{ sales_invoice.employee_fn + " " + sales_invoice.employee_sn }}</td>
                                        <td>{{ sales_invoice.sales_comment }}</td>
                                        <td>{{ sales_invoice.sales_status }}</td>
                                        <td>{{ sales_invoice.created_at }}</td>
                                        <td><i v-if="sales_invoice.sales_status == 'not remitted'" class="fas fa-ban" style="cursor: pointer;" @click="voidSales(sales_invoice.id)"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 2">
                        <div class="col-12">
                            <div v-if="loaded">
                                <pie :chartData="pie" :options="pieOptions"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 3">
                        <div class="col-12" v-if="ps_select == 1">
                            <table class="table table-sm table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Unit Cost</th>
                                        <th>Sold</th>
                                        <th>Total Cost</th>
                                        <th>Total Price</th>
                                        <th>Discount</th>
                                        <th>Vat Amount</th>
                                        <th>Total Amount</th>
                                        <th>Total Profit</th>
                                    </tr>
                                </thead>
                                <tbody v-for="sales_prod in sales_prods" :key="sales_prod.id">
                                    <tr>
                                        <td class="align-middle">{{ sales_prod.product_name }}</td>
                                        <td class="align-middle">{{ sales_prod.category_name }}</td>
                                        <td class="align-middle">&#8369; {{ sales_prod.unit_price }}</td>
                                        <td class="align-middle">{{ sales_prod.tot_qty }}</td>
                                        <td class="align-middle">&#8369; {{ (sales_prod.unit_price * sales_prod.tot_qty).toFixed(2) }}</td>
                                        <td class="align-middle">&#8369; {{ sales_prod.tot_amount }}</td>
                                        <td class="align-middle">&#8369; {{ sales_prod.tot_disc }}</td>
                                        <td class="align-middle">&#8369; {{ sales_prod.tot_vat }}</td>
                                        <td class="align-middle">&#8369; {{ (sales_prod.tot_amount - sales_prod.tot_vat - sales_prod.tot_disc).toFixed(2) }}</td>
                                        <td class="align-middle">&#8369; {{ ((sales_prod.tot_amount - sales_prod.tot_vat - sales_prod.tot_disc) - (sales_prod.unit_price * sales_prod.tot_qty)).toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-right">TOTAL:</td>
                                        <td class="text-center">&#8369; {{ unitCostTotal }}</td>
                                        <td class="text-center">&#8369; {{ salesTotal }}</td>
                                        <td class="text-center">&#8369; {{ discountTotal }}</td>
                                        <td class="text-center">&#8369; {{ vatTotal }}</td>
                                        <td class="text-center">&#8369; {{ totalAmount }}</td>
                                        <td class="text-center">&#8369; {{ totalProfit }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12" v-else-if="ps_select == 2">
                            <table class="table table-sm table-hover text-center">
                                <thead>
                                    <th>Category Name</th>
                                    <th>Sold</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Total Price</th>
                                    <th>Discount</th>
                                    <th>Vat</th>
                                    <th>Total Amount</th>
                                    <th>Total Profit</th>
                                </thead>
                                <tbody v-for="sales_cat in sales_cats" :key="sales_cat.category_id">
                                    <tr>
                                        <td class="align-middle">{{ sales_cat.category_name }}</td>
                                        <td class="align-middle">{{ sales_cat.tot_qty }}</td>
                                        <td class="align-middle">&#8369; {{ sales_cat.unit_price }}</td>
                                        <td class="align-middle">&#8369; {{ sales_cat.tot_cost }}</td>
                                        <td class="align-middle">&#8369; {{ sales_cat.tot_amount }}</td>
                                        <td class="align-middle">&#8369; {{ sales_cat.tot_disc }}</td>
                                        <td class="align-middle">&#8369; {{ sales_cat.tot_vat }}</td>
                                        <td class="align-middle">&#8369; {{ (sales_cat.tot_amount - sales_cat.tot_vat - sales_cat.tot_disc).toFixed(2) }}</td>
                                        <td class="align-middle">&#8369; {{ ((sales_cat.tot_amount - sales_cat.tot_vat - sales_cat.tot_disc) - (sales_cat.tot_cost)).toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr class="font-weight-bold">
                                        <td colspan="3" class="text-right">TOTAL:</td>
                                        <td class="text-center">&#8369; {{ unitCostTotal }}</td>
                                        <td class="text-center">&#8369; {{ salesTotal }}</td>
                                        <td class="text-center">&#8369; {{ discountTotal }}</td>
                                        <td class="text-center">&#8369; {{ vatTotal }}</td>
                                        <td class="text-center">&#8369; {{ totalAmount }}</td>
                                        <td class="text-center">&#8369; {{ totalProfit }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 item-bottom mb-3">
                            <button class="btn btn-sm btn-primary" @click="printSales()"><span class="float-left ml-2"><i class="fas fa-print"></i> Print All</span></button>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 4">
                        <div class="col-12">
                            <div v-if="loaded">
                                <bar :chartData="barSingle" :options="barOptions"/>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" v-if="selectedMenu == 5">
                        <div class="col-12">
                            <div v-if="loaded">
                                <bar :chartData="bar" :options="barOptions"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Bar from '../views/sales-report/Bar.vue';
import Pie from '../views/sales-report/Pie.vue';
import axios from "axios";
import moment from "moment";
export default {
    components: { Bar, Pie },
    data () {
        return {
            menus: [
                {
                    id: 1,
                    menu_name: "Sales Invoice"
                },
                {
                    id: 2,
                    menu_name: "Sold Ranking"
                },
                {
                    id: 3,
                    menu_name: "Product Sales"
                },
                {
                    id: 4,
                    menu_name: "X Read"
                },
                {
                    id: 5,
                    menu_name: "Z Read"
                }
            ],
            headerName: "Sales Invoice",
            selectedMenu: null,
            loaded: false,
            bar: [],
            barSingle: [],
            pie: [],
            barOptions: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                        callback: function(value) {
                            if (parseInt(value) >= 1000) {
                                return '₱' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return '₱' + value;
                            }
                        }
                        }
                    }]
                },
                responsive: true,
                maintainAspectRatio: false
            },
            pieOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            employees: [],
            sales_prods: [],
            sales_cats: [],
            sales_invoices: [],
            ps_select: 1,
            customer_id: 1,
            payment_id: 1,
            z_employee_id: 0,
            x_employee_id: 0,
            payment_types: [],
            customers: [],
            rank_from_date: new Date(),
            rank_to_date: new Date(),
            x_from_date: new Date(),
            z_from_date: new Date(),
            z_to_date: new Date(),
            ps_from_date: new Date(),
            ps_to_date: new Date(),
            inv_from_date: new Date(),
            inv_to_date: new Date()
        }
    },
    created() {
        this.rank_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.rank_to_date = moment().endOf("month").format("YYYY-MM-DD");
        this.x_from_date = moment().format("YYYY-MM-DD");
        this.z_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.z_to_date = moment().endOf("month").format("YYYY-MM-DD");
        this.ps_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.ps_to_date = moment().endOf("month").format("YYYY-MM-DD");
        this.inv_from_date = moment().startOf("month").format("YYYY-MM-DDTHH:mm");
        this.inv_to_date = moment().endOf("month").format("YYYY-MM-DDTkk:mm");
        this.fetchSalesInvoice(this.payment_id, this.customer_id, this.inv_from_date, this.inv_to_date);
        this.fetchPaymentTypes();
        this.fetchCustomers();
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        fetchSales(employee_id, from_date, to_date) {
            let app = this;

            app.loaded = false;

            axios
                .get("/api/getSalesByDate/?frdate=" + from_date + "&tdate=" + to_date + "&employee_id=" + employee_id)
                .then(function(response) {
                    app.loaded = true;
                    const responseData = response.data.data;
                    
                    var arr_dt = [];
                    var arr_tot = [];

                    responseData.forEach(element => {
                        arr_dt.push(moment(element.sales_date).format('MMM DD, YYYY'));
                        arr_tot.push(element.sales_total);
                    });

                    app.bar = {
                        labels: arr_dt,
                        datasets: [
                            {
                                backgroundColor: '#5b86e5',
                                data: arr_tot
                            }
                        ]
                    }
                    
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSalesSingle(employee_id, from_date) {
            let app = this;

            app.loaded = false;

            axios
                .get("/api/getSalesByDate/?frdate=" + from_date + "&employee_id=" + employee_id)
                .then(function(response) {
                    app.loaded = true;
                    const responseData = response.data.data;
                    
                    var arr_dt = [];
                    var arr_tot = [];

                    responseData.forEach(element => {
                        arr_dt.push(moment(element.sales_date).format('MMM DD, YYYY'));
                        arr_tot.push(element.sales_total);
                    });

                    app.barSingle = {
                        labels: arr_dt,
                        datasets: [
                            {
                                backgroundColor: '#5b86e5',
                                data: arr_tot
                            }
                        ]
                    }
                    
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSalesRanking(from_date, to_date) {
            let app = this;

            app.loaded = false;

            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/getSalesRankingByDate/?frdate=" + from_date + "&tdate=" + to_date)
                    .then(function(response) {
                        app.loaded = true;
                        const responseData = response.data.data;

                        var arr_prod = [];
                        var arr_tot = [];

                        responseData.forEach(element => {
                            arr_prod.push(element.product_name + " (" + element.total_qty + ")");
                            arr_tot.push(element.total_qty);
                        });

                        app.pie = {
                            labels: arr_prod,
                            datasets: [
                                {
                                    backgroundColor: ['#5b86e5', '#c91162', '#4f3947', '#1a355b', '#a3f3a6'],
                                    data: arr_tot
                                }
                            ]
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 100);
        },
        fetchSalesProduct(cat, from_date, to_date) {
            let app = this;
            
                axios
                    .get("/api/getAllSalesProduct/?frdate=" + from_date + "&tdate=" + to_date)
                    .then(function(response) {
                        const responseData = response.data.data;
                        app.sales_prods = responseData.filter(item => item.tot_qty != 0);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                axios
                    .get("/api/getAllSalesCategory/?frdate=" + from_date + "&tdate=" + to_date)
                    .then(function(response) {
                        const responseData = response.data.data;
                        app.sales_cats = responseData.filter(item => item.tot_qty != 0);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
        },
        fetchEmployees() {
            let app = this;

            axios
                .get("/api/getAllEmployees/?status=active")
                .then(function(response) {
                    app.loaded = true;
                    app.z_employees = response.data.data;
                    app.x_employees = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchPaymentTypes() {
            let app = this;

            axios
                .get("/api/getAllPaymentTypes/")
                .then(function(response) {
                    app.payment_types = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCustomers() {
            let app = this;

            axios
                .get("/api/getAllCustomers/?customer_status=active")
                .then(function(response) {
                    app.customers = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchSalesInvoice(payment_id, customer_id, from_date, to_date) {
            let app = this;

            axios
                .get("/api/getAllSalesInvoice/?status=active&frdate=" + from_date + "&tdate=" + to_date + "&payment_id=" + payment_id + "&customer_id=" + customer_id)
                .then(function(response) {
                    app.sales_invoices = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        voidSales(sales_id) {
            let app = this;
            if (confirm("Are you sure to void this Invoice ID #" + sales_id)) {
                axios
                .put("/api/updateSalesInvoice/", {'sales_id':sales_id})
                .then(function() {
                    app.fetchSalesInvoice(app.payment_id, app.customer_id, app.inv_from_date, app.inv_to_date);
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        },
        printSales() {
            let routeData = this.$router.resolve({name: 'Print Sales', query: {typeid: this.ps_select, fdate: this.ps_from_date, tdate: this.ps_to_date}});
            window.open(routeData.href, '_blank', "height=500,width=900");
        }
    },
    computed: {
        salesTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_amount)
            });
            return (sum).toFixed(2);
        },
        unitCostTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.unit_price * element.tot_qty)
            });
            return (sum).toFixed(2);
        },
        vatTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_vat)
            });
            return sum.toFixed(2);
        },
        discountTotal: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_disc)
            });
            return sum.toFixed(2);
        },
        totalAmount: function() {
            var sum=0;
            this.sales_prods.forEach(element => {
                sum += parseFloat(element.tot_amount - element.tot_vat - element.tot_disc)
            });
            return (sum).toFixed(2);
        },
        totalProfit: function() {
            return (this.totalAmount - this.unitCostTotal).toFixed(2);
        },
    }
}
</script>