<template>
    <div class="row">
        <div class="col-sm-2">
            <div class="card fullheight rounded-0" style="overflow: auto;">
                <div class="card-header bg-gradient text-white rounded-0 p-3">
                    <h5>Settings Menus</h5> 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(menu, index) in menus" :value="menu.id" :key="menu.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{'active' : (selectedMenu !== null) ? menu.id == selectedMenu : index===0}" @click="selectedMenu = menu.id, headerName=menu.menu_name, (selectedMenu == 3) ? flush() : fetchEmployeesById($session.get('user_id')), (selectedMenu == 3) ? edit=true : edit=false">{{ menu.menu_name }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary text-white btn-block sticky-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
        </div>
        <div class="col-sm-10">
            <div class="card fullheight rounded-0" style="overflow: auto;">
                <div class="card-header bg-gradient text-white rounded-0 p-3">
                    <div class="col-sm-2"><h5>{{ headerName }}</h5> </div>
                </div>
                <div class="card-body">
                    <div class="row" v-if="selectedMenu === null || selectedMenu == 1 || selectedMenu == 3">
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="text" v-model="employee.employee_fn" id="fn" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_fn}" placeholder="First Name">
                                <label for="fn">First Name</label>
                                <small class="text-danger font-weight-bold" v-if="errors.employee_fn">{{ errors.employee_fn }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="text" v-model="employee.employee_mn" id="mn" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Maiden Name (optional)">
                                <label for="mn">Maiden Name (optional)</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="text" v-model="employee.employee_sn" id="sn" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_sn}" placeholder="Surname">
                                <label for="sn">Surname</label>
                                <small class="text-danger font-weight-bold" v-if="errors.employee_sn">{{ errors.employee_sn }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="ml-4 text-muted"><small>Province</small></label>
                            <select v-model="employee.employee_prov" @input="fetchCityMuns($event.target.value), citymuns=[], brgys=[]" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_prov}">
                                <option class="text-dark" value="0" disabled>Select Province</option>
                                <option class="text-dark" v-for="province in provinces" :key="province.id" :value="province.provCode">{{ province.provDesc }}</option>
                            </select>
                            <small class="text-danger font-weight-bold" v-if="errors.employee_prov">{{ errors.employee_prov }}</small>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="ml-4 text-muted"><small>City/Municipality</small></label>
                            <select v-model="employee.employee_citymun" @input="fetchBrgys($event.target.value), brgys=[]" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_citymun}">
                                <option class="text-dark" value="0" disabled>Select City</option>
                                <option class="text-dark" v-for="citymun in citymuns" :key="citymun.id" :value="citymun.citymunCode">{{ citymun.citymunDesc }}</option>
                            </select>
                            <small class="text-danger font-weight-bold" v-if="errors.employee_citymun">{{ errors.employee_citymun }}</small>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="ml-4 text-muted"><small>Barangay</small></label>
                            <select v-model="employee.employee_brgy" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_brgy}">
                                <option class="text-dark" value="0" disabled>Select Baranggay</option>
                                <option class="text-dark" v-for="brgy in brgys" :key="brgy.brgyCode" :value="brgy.brgyCode">{{ brgy.brgyDesc }}</option>
                            </select>
                            <small class="text-danger font-weight-bold" v-if="errors.employee_brgy">{{ errors.employee_brgy }}</small>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="number" v-model="employee.employee_phone" id="phone" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0 mt-3" :class="{'is-invalid': errors.employee_phone}" placeholder="Contact #">
                                <label for="phone">Contact #</label>
                                <small class="text-danger font-weight-bold" v-if="errors.employee_phone">{{ errors.employee_phone }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="alert alert-success" role="alert" v-if="msg">
                                {{ msg }}
                            </div>
                        </div>
                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-sm btn-block btn-outline-info" @click="updateAccount">Save</button>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 2">
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="password" v-model="oldPass" id="cp" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Current Password">
                                <label for="cp">Current Password</label>
                                <small class="text-danger font-weight-bold" v-if="errors.oldPass">{{ errors.oldPass }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="password" v-model="newPass" id="np" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Enter New Password">
                                <label for="np">Enter New Password</label>
                                <small class="text-danger font-weight-bold" v-if="errors.newPass">{{ errors.newPass }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-label-group">
                                <input type="password" v-model="confirmPass" id="cnp" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" placeholder="Confirm New Password">
                                <label for="cnp">Confirm New Password</label>
                                <small class="text-danger font-weight-bold" v-if="errors.confirmPass">{{ errors.confirmPass }}</small>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="alert alert-success" role="alert" v-if="msgpass">
                                {{ msgpass }}
                            </div>
                        </div>
                        <div class="col-sm-2 offset-sm-4 mt-3">
                            <button class="btn btn-sm btn-outline-info btn-block" @click="changePass">Save</button>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 3">
                        <div class="col-sm-12">
                            <table class="table table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Contact#</th>
                                        <th>Address</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody v-for="emp in emps" :key="emp.id">
                                    <tr>
                                        <td>{{ emp.employee_fn + ' ' + emp.employee_sn }}</td>
                                        <td>{{ emp.employee_phone }}</td>
                                        <td>{{ emp.brgyDesc + ', ' + emp.citymunDesc + ' ' + emp.provDesc }}</td>
                                        <td>
                                            <i class="fa fa-edit mr-2" :title="'Click to Edit ' + emp.employee_fn + ' ' + emp.employee_sn" style="cursor: pointer;" @click="editEmployee(emp)"></i> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 4">
                        <div class="col-sm-4 mt-3">
                            <div class="form-label-group">
                                <input type="text" v-model="customer.customer_name" id="cusname" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.customer_name}" placeholder="Customer Name">
                                <label for="cusname">Customer Name</label>
                                <small class="text-danger font-weight-bold" v-if="errors.customer_name">{{ errors.customer_name }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4 mt-3">
                            <div class="form-label-group">
                                <input type="number" v-model="customer.customer_phone" id="cph" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.customer_phone}" placeholder="Customer Contact#">
                                <label for="cph">Customer Contact#</label>
                                <small class="text-danger font-weight-bold" v-if="errors.customer_phone">{{ errors.customer_phone }}</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="" class="ml-4 text-muted"><small>Status</small></label>
                            <select v-model="customer.customer_status" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="1">Active</option>
                                <option class="text-dark" value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="alert alert-success" role="alert" v-if="msg">
                                {{ msg }}
                            </div>
                        </div>
                        <div class="col-sm-2 offset-sm-4 mt-3 mb-3">
                            <button class="btn btn-sm btn-outline-info btn-block" @click="addCustomer">Save</button>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Contact#</th>
                                        <th>Status</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody v-for="cust in custs" :key="cust.id">
                                    <tr>
                                        <td>{{ cust.customer_name }}</td>
                                        <td>{{ cust.customer_phone }}</td>
                                        <td>{{ cust.customer_status }}</td>
                                        <td>
                                            <i class="fa fa-edit mr-2" v-if="cust.id!=1" :title="'Click to Edit ' + cust.customer_name" style="cursor: pointer;" @click="editCustomer(cust)"></i> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" v-if="selectedMenu == 5">
                        <div class="col-sm-3">
                            <label for="user-emp" class="ml-4 text-muted"><small>Employees</small></label>
                            <select id="user-emp" v-model="user.employee_id" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.employee_id}">
                                <option class="text-dark" value="0" disabled>Select Employee</option>
                                <option class="text-dark" v-for="emp in emp_users" :key="emp.id" :value="emp.id">{{ emp.employee_fn + " " + emp.employee_sn }}</option>
                            </select>
                            <small class="text-danger font-weight-bold" v-if="errors.employee_id">{{ errors.employee_id }}</small>
                        </div>
                        <div class="col-sm-3 mt-3">
                            <div class="form-label-group">
                                <input type="text" v-model="user.username" id="usernames" class="form-control form-control-sm bg-transparent rounded-0 border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.username}" placeholder="Username">
                                <label for="usernames">Username</label>
                                <small class="text-danger font-weight-bold" v-if="errors.username">{{ errors.username }}</small>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="" class="ml-4 text-muted"><small>Status</small></label>
                            <select v-model="user.user_status" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="1">Active</option>
                                <option class="text-dark" value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="" class="ml-4 text-muted"><small>Account Approval</small></label>
                            <select v-model="user.user_approval" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0">
                                <option class="text-dark" value="1">Cashier</option>
                                <option class="text-dark" value="2">Manager</option>
                            </select>
                        </div>
                        <div class="col-sm-10 mt-3">
                            <div class="alert alert-success" role="alert" v-if="msguser">
                                {{ msguser }}
                            </div>
                        </div>
                        <div class="col-sm-2 mt-3">
                            <button class="btn btn-sm btn-outline-info btn-block" @click="addUser">Save</button>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Username</th>
                                        <th>Approval</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody v-for="usrs in usr" :key="usrs.id">
                                    <tr>
                                        <td>{{ usrs.employee_fn + ' ' + usrs.employee_sn }}</td>
                                        <td>{{ usrs.username }}</td>
                                        <td>{{ usrs.user_approval }}</td>
                                        <td>{{ usrs.user_status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data () {
        return {
            menus: [
                {
                    id: 1,
                    menu_name: "My Account"
                },
                {
                    id: 2,
                    menu_name: "Change Password"
                },
                {
                    id: 3,
                    menu_name: "Employees"
                },
                {
                    id: 4,
                    menu_name: "Customers"
                },
                {
                    id: 5,
                    menu_name: "Users"
                },
                {
                    id: 6,
                    menu_name: "Printer Settings"
                }
            ],
            headerName: "My Account",
            selectedMenu: null,
            employee: {
                id: this.$session.get("user_id"),
                employee_fn: '',
                employee_mn: '',
                employee_sn: '',
                employee_prov: 0,
                employee_citymun: 0,
                employee_brgy: 0,
                employee_phone: 0
            },
            user: {
                employee_id: 0,
                username: '',
                user_approval: 1,
                user_status: 1
            },
            customer: {
                customer_name: '',
                customer_phone: '',
                customer_status: 1,
            },
            provinces: [],
            citymuns: [],
            brgys: [],
            errors: [],
            edit: false,
            edit_customer: false,
            msg: false,
            msgpass: false,
            msguser: false,
            oldPass: '',
            newPass: '',
            confirmPass: '',
            emps: [],
            custs: [],
            emp_users: [],
            usr: []
        }
    },
    mounted() {
        if(this.$session.get('user_approval') == 'cashier') {
            var removeIndex = [4, 5];
            for (let index = removeIndex.length -1; index >= 0; index--) {
                this.menus.splice(removeIndex[index], 1)
            }
        }
    },
    created() {
        this.fetchProvinces();
        this.fetchEmployeesById(this.$session.get("user_id"));
        this.fetchEmployees();
        this.fetchCustomers();
        this.fetchEmployeesWithoutUser();
        this.fetchUsers();
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        flush() {
            this.employee = {
                id: this.$session.get("user_id"),
                employee_fn: '',
                employee_mn: '',
                employee_sn: '',
                employee_prov: 0,
                employee_citymun: 0,
                employee_brgy: 0,
                employee_phone: 0
            }
        },
        validFName() {
            return this.employee.employee_fn.length <= 1;
        },
        validSName() {
            return this.employee.employee_sn.length <= 1;
        },
        validContact() {
            return this.employee.employee_phone.length <= 5 || this.employee.employee_phone == 0;
        },
        validProvince () { 
            return this.employee.employee_prov == 0;
        },
        validCity () { 
            return this.employee.employee_citymun == 0;
        },
        validBarangay () { 
            return this.employee.employee_brgy == 0;
        },
        validCurrentPass () { 
            return this.oldPass == '' || this.oldPass.length < 5;
        },
        validNewPass () { 
            return this.newPass == '' || this.newPass.length < 5;
        },
        validCNewPass () { 
            return this.confirmPass == '' || this.oldPass.length < 5;
        },
        validSamePass() {
            return this.newPass != this.confirmPass;
        },
        validCusName() {
            return this.customer.customer_name.length <= 1;
        },
        validCusNum() {
            return this.customer.customer_phone.length <= 5 || this.customer.customer_phone == 0;
        },
        validUsername() {
            return this.user.username.length <= 3;
        },
        validEmployee () { 
            return this.user.employee_id == 0;
        },
        fetchEmployeesById(user_id) {
            var app = this;

            axios
                .get("/api/getEmployeesById/?status=active&id=" + user_id)
                .then(function(response) {
                    var resData = response.data.data;
                    resData.forEach(element => {
                        app.employee.employee_fn = element.employee_fn;
                        app.employee.employee_mn = element.employee_mn;
                        app.employee.employee_sn = element.employee_sn;
                        app.employee.employee_prov = element.employee_prov;
                        app.employee.employee_citymun = element.employee_citymun;
                        app.employee.employee_brgy = element.employee_brgy;
                        app.employee.employee_phone = element.employee_phone;
                    });
                    app.fetchCityMuns(app.employee.employee_prov);
                    app.fetchBrgys(app.employee.employee_citymun);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchEmployees() {
            var app = this;

            axios
                .get("/api/getAllEmployees/?status=active&id=" + app.$session.get('user_id'))
                .then(function(response) {
                    app.emps = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchEmployeesWithoutUser() {
            var app = this;

            axios
                .get("/api/getAllEmployeesWithoutUser/?id=" + app.$session.get('user_id'))
                .then(function(response) {
                    app.emp_users = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCustomers() {
            var app = this;

            axios
                .get("/api/getAllCustomers/?customer_status=")
                .then(function(response) {
                    app.custs = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchProvinces() {
            var app = this;

            axios
                .get("/api/getAllProvinces/")
                .then(function(response) {
                    app.provinces = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCityMuns(provid) {
            var app = this;
            axios
                .get("/api/getCityMun/?provCode=" + provid)
                .then(function(response) {
                    app.citymuns = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchBrgys(citymunid) {
            var app = this;

            axios
                .get("/api/getBarangay/?citymunCode=" + citymunid)
                .then(function(response) {
                    app.brgys = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        updateAccount() {
            var app = this;
            app.errors = [];
            
            if (app.validSName()) app.errors.employee_sn = "Please input your Surname.";
            if (app.validFName()) app.errors.employee_fn = "Please input your Firstname.";
            if (app.validProvince()) app.errors.employee_prov = "Please Select Province.";
            if (app.validCity()) app.errors.employee_citymun = "Please Select City.";
            if (app.validBarangay()) app.errors.employee_brgy = "Please Select Barangay.";
            if (app.validContact()) app.errors.employee_phone = "Please input your Contact.";

            if (
                !app.validSName() &&
                !app.validFName() &&
                !app.validCity() &&
                !app.validBarangay() &&
                !app.validContact() &&
                !app.validProvince()
            ) 
            {
                if (app.edit == true) {
                    axios
                        .post("/api/addEmployee/", app.employee)
                        .then(function() {
                            app.msg = "Employee Added Successfully!";
                            app.fetchEmployees();
                            setTimeout(() => {
                                app.msg = false;
                            }, 3000);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    axios
                        .put("/api/addEmployee/", app.employee)
                        .then(function() {
                            app.msg = "Employee Updated Successfully!";
                            app.fetchEmployees();
                            setTimeout(() => {
                                app.msg = false;
                            }, 3000);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        },
        changePass() {
            var app = this;
            app.errors = [];

            if (app.validCurrentPass()) app.errors.oldPass = "Please input current password more than 5 characters.";
            if (app.validNewPass()) app.errors.newPass = "Please input new password more than 5 characters.";
            if (app.validSamePass()) app.errors.newPass = "Password Not Match.";
            if (app.validCNewPass()) app.errors.confirmPass = "Please input new password more than 5 characters.";
            

            if (
                !app.validCurrentPass() &&
                !app.validNewPass() &&
                !app.validCNewPass() &&
                !app.validSamePass()
            ) {
                axios
                    .put("/api/updateUserPassword/", {'oldpass': app.oldPass, 'newpass': app.newPass, 'id': app.$session.get('user_id')})
                    .then(function() {
                        app.oldPass='';
                        app.newPass='';
                        app.confirmPass='';
                        app.msgpass = "Password updated successfully!!";
                        setTimeout(() => {
                            app.msgpass = false;
                        }, 3000);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        editEmployee(emp) {
            let app = this;
            app.edit = false;

            app.employee.id = emp.id;
            app.employee.employee_fn = emp.employee_fn;
            app.employee.employee_mn = emp.employee_mn;
            app.employee.employee_sn = emp.employee_sn;
            app.employee.employee_prov = emp.employee_prov;
            app.employee.employee_citymun = emp.employee_citymun;
            app.employee.employee_brgy = emp.employee_brgy;
            app.employee.employee_phone = emp.employee_phone;
            app.fetchCityMuns(emp.employee_prov);
            app.fetchBrgys(emp.employee_citymun);
        },
        addCustomer() {
            var app = this;
            app.errors = [];
            
            if (app.validCusName()) app.errors.customer_name = "Please Customer name.";
            if (app.validCusNum()) app.errors.customer_phone = "Please Input Customer Phone Number.";

            if (
                !app.validCusName() &&
                !app.validCusNum()
            ) {
                if (app.edit == true) {
                    axios
                        .post("/api/addCustomer/", app.customer)
                        .then(function() {
                            app.msg = "Customer Added Successfully!";
                            app.fetchCustomers();
                            setTimeout(() => {
                                app.msg = false;
                            }, 3000);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    axios
                        .put("/api/addCustomer/", app.customer)
                        .then(function() {
                            app.msg = "Customer Updated Successfully!";
                            app.fetchCustomers();
                            setTimeout(() => {
                                app.msg = false;
                            }, 3000);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        },
        editCustomer(emp) {
            let app = this;
            app.edit_customer = false;

            app.customer.id = emp.id;
            app.customer.customer_name = emp.customer_name;
            app.customer.customer_phone = emp.customer_phone;
            app.customer.customer_status = (emp.customer_status == "active") ? 1 : 2;
        },
        addUser() {
            let app = this;
            app.errors = [];
            
            if (app.validUsername()) app.errors.username = "Please input username.";
            if (app.validEmployee()) app.errors.employee_id = "Please select employee.";

            if (
                !app.validUsername() &&
                !app.validEmployee() 
            ) {
                axios
                    .post("/api/addUser/", app.user)
                    .then(function() {
                        app.msguser = "User Added Successfully!";
                        app.fetchEmployeesWithoutUser();
                        setTimeout(() => {
                            app.msguser = false;
                        }, 3000);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        fetchUsers() {
            var app = this;

            axios
                .get("/api/getAllUsers/")
                .then(function(response) {
                    app.usr = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
}
</script>