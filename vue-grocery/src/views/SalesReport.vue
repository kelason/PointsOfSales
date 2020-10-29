<template>
    <div class="row">
        <div class="col-sm-2">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient text-white rounded-0">
                    <h5>Menus</h5> 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(menu, index) in menus" :value="menu.id" :key="menu.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{'active' : (selectedMenu !== null) ? menu.id == selectedMenu : index===0}" @click="selectedMenu = menu.id, headerName=menu.menu_name, fetchSales(z_employee_id, z_from_date, z_to_date), fetchSalesSingle(x_employee_id, x_from_date), fetchEmployees()">{{ menu.menu_name }}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary text-white btn-block item-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient text-white rounded-0 pt-0 pb-0">
                    <div class="row" v-if="selectedMenu === null || selectedMenu == 1">
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
                    <div class="row" v-if="selectedMenu == 4">
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
                            <div v-if="loaded">
                                <pie :chartData="pie" :options="pieOptions"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 2">
                        <div class="col-12">
                            <h5 class="font-weight-bold">Item Lists</h5>
                        </div>
                        <hr>
                    </div>
                    <div class="row" v-if="selectedMenu == 3">
                        <div class="col-12">
                            <div v-if="loaded">
                                <bar :chartData="barSingle" :options="barOptions"/>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row" v-if="selectedMenu == 4">
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
                    menu_name: "Sold Ranking"
                },
                {
                    id: 2,
                    menu_name: "Product Sales"
                },
                {
                    id: 3,
                    menu_name: "X Read"
                },
                {
                    id: 4,
                    menu_name: "Z Read"
                }
            ],
            headerName: "Sold Ranking",
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
            z_employee_id: 0,
            x_employee_id: 0,
            rank_from_date: new Date(),
            rank_to_date: new Date(),
            x_from_date: new Date(),
            z_from_date: new Date(),
            z_to_date: new Date()
        }
    },
    created() {
        this.rank_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.rank_to_date = moment().endOf("month").format("YYYY-MM-DD");
        this.x_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.z_from_date = moment().startOf("month").format("YYYY-MM-DD");
        this.z_to_date = moment().endOf("month").format("YYYY-MM-DD");
        this.fetchSalesRanking(this.rank_from_date, this.rank_to_date);
    },
    methods: {
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
                            arr_prod.push(element.product_name);
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
    }
}
</script>