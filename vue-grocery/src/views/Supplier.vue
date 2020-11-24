<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-label-group">
                                <input type="text" id="searchSupplier" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product" @keyup="searchSupplier($event.target.value)">
                                <label for="searchSupplier" class="text-white">Search Supplier</label>
                            </div>
                        </div>
                        <div class="col-1 offset-6 mt-3">
                            <i class="fas fa-plus-circle fa-2x" title="Add Suppliers" @click="toggleModal(), edit = false;" style="cursor: pointer;"></i>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body overflow-auto">
                    <form method="POST" enctype="multipart/form-data">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>Supplier Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="suppliers==''">
                                <tr class="text-center">
                                    <td colspan="9">
                                        <p class="text-danger text-center">No Records...</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr class="text-center" v-for="supplier in suppliers" :key="supplier.id">
                                    <td class="align-middle">{{ supplier.supplier_name }}</td>
                                    <td class="align-middle">{{ supplier.supplier_phone }}</td>
                                    <td class="align-middle">{{ supplier.brgyDesc + ", " + supplier.citymunDesc + ", " + supplier.provDesc }}</td>
                                    <td class="align-middle">
                                        <i class="fa fa-edit mr-2" :title="'Click to Edit ' + supplier.supplier_name" style="cursor: pointer;" @click="editSupplier(supplier)"></i> 
                                        <i class="fa fa-trash ml-2" :title="'Click to Delete ' + supplier.supplier_name" style="cursor: pointer;" @click="deleteSupplier(supplier.id, supplier.supplier_name)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <hr>
                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1} || pagination == '']" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchSuppliers(page = page - 1)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchSuppliers(page = page + 1)">
                                            Next
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addSupplier">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="supplier.supplier_name" id="supname" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Supplier Name">
                                    <label for="supname">Supplier Name</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_name">{{ errors.supplier_name }}</small>
                            </div>
                            <div class="col-6">
                                <div class="form-label-group">
                                    <input type="text" v-model="supplier.supplier_phone" id="supphone" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Supplier Contact">
                                    <label for="supphone">Supplier Contact</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_phone">{{ errors.supplier_phone }}</small>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="ml-4 text-muted"><small>Province</small></label>
                                <select v-model="supplier.supplier_prov" @input="fetchCityMuns($event.target.value), citymuns=[], brgys=[]" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.supplier_prov}">
                                    <option class="text-dark" value="0" disabled>Select Province</option>
                                    <option class="text-dark" v-for="province in provinces" :key="province.id" :value="province.provCode">{{ province.provDesc }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_prov">{{ errors.supplier_prov }}</small>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="ml-4 text-muted"><small>City/Municipality</small></label>
                                <select v-model="supplier.supplier_citymun" @input="fetchBrgys($event.target.value), brgys=[]" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.supplier_citymun}">
                                    <option class="text-dark" value="0" disabled>Select City</option>
                                    <option class="text-dark" v-for="citymun in citymuns" :key="citymun.id" :value="citymun.citymunCode">{{ citymun.citymunDesc }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_citymun">{{ errors.supplier_citymun }}</small>
                            </div>
                            <div class="col-sm-4">
                                <label for="" class="ml-4 text-muted"><small>Barangay</small></label>
                                <select v-model="supplier.supplier_brgy" class="form-control form-control-sm rounded-0 bg-transparent border-top-0 border-left-0 border-right-0" :class="{'is-invalid': errors.supplier_brgy}">
                                    <option class="text-dark" value="0" disabled>Select Baranggay</option>
                                    <option class="text-dark" v-for="brgy in brgys" :key="brgy.brgyCode" :value="brgy.brgyCode">{{ brgy.brgyDesc }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.supplier_brgy">{{ errors.supplier_brgy }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert" v-if="msg">
                                    {{msg}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addSupplier">Save changes</button>
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
            suppliers: [],
            category_types: [],
            supplier: {
                id: 0,
                supplier_name: '',
                supplier_prov: 0,
                supplier_citymun: 0,
                supplier_brgy: 0,
                supplier_phone: 0
            },
            active: false,
            show: false,
            pagination: [],
            edit: false,
            errors: [],
            page: 1,
            msg: null,
            provinces: [],
            citymuns: [],
            brgys: []
        }
    },
    created () {
        this.fetchSuppliers(this.page);
        this.fetchProvinces();
    },
    computed: {
        validCategoryName: function() {
            return this.category.category_name.length <= 1;
        },
        validCategoryType: function() {
            return this.category.type_id == 0;
        },
        validStatus: function() {
            return this.category.category_status == 0;
        }
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        validSupName() {
            return this.supplier.supplier_name.length <= 1;
        },
        validContact() {
            return this.supplier.supplier_phone.length <= 5 || this.supplier.supplier_phone == 0;
        },
        validProvince () { 
            return this.supplier.supplier_prov == 0;
        },
        validCity () { 
            return this.supplier.supplier_citymun == 0;
        },
        validBarangay () { 
            return this.supplier.supplier_brgy == 0;
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        fetchSuppliers(page) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllSuppliers/?page=" + page)
                .then(function(response) {
                    app.suppliers = response.data.data;
                    app.pagination = response.data.pagination;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchProvinces() {
            var app = this;
            const axios = require("axios");
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
            const axios = require("axios");
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
            const axios = require("axios");
            axios
                .get("/api/getBarangay/?citymunCode=" + citymunid)
                .then(function(response) {
                    app.brgys = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        searchSupplier(name) {
            var app = this;
            const axios = require("axios");
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchSupplier/?supplier_name=" + name + "&page=" + app.page)
                    .then(function(response) {
                        app.suppliers = response.data.data;
                        app.pagination = response.data.pagination;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
                
        },
        addSupplier() {
            var app = this;
            app.errors = [];

            if (app.validSupName()) app.errors.supplier_name = "Please input Supplier Name.";
            if (app.validContact()) app.errors.supplier_phone = "Please input Supplier Contact.";
            if (app.validProvince()) app.errors.supplier_prov = "Please Select Province.";
            if (app.validCity()) app.errors.supplier_citymun = "Please Select City.";
            if (app.validBarangay()) app.errors.supplier_brgy = "Please Select Barangay.";

            if(
                !app.validSupName() &&
                !app.validContact() &&
                !app.validProvince() &&
                !app.validCity() &&
                !app.validBarangay() 
            ) {
                const axios = require("axios");
                if(app.edit == false) {
                    axios
                        .post("/api/addSupplier/", app.supplier)
                        .then(function(response) {
                            app.msg = response.data.msg;

                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchSuppliers(app.page);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    axios
                        .put("/api/updateSupplier/", app.supplier)
                        .then((response) => {
                            app.msg = response.data.msg;
                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchSuppliers(app.page);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        },
        editSupplier(sup) {
            let app = this;
            app.toggleModal();

            app.edit = true;

            app.supplier.id = sup.id;
            app.supplier.supplier_name = sup.supplier_name;
            app.supplier.supplier_phone = sup.supplier_phone;
            app.supplier.supplier_prov = sup.supplier_prov;
            app.supplier.supplier_citymun = sup.supplier_citymun;
            app.supplier.supplier_brgy = sup.supplier_brgy;
            app.fetchCityMuns(sup.supplier_prov);
            app.fetchBrgys(sup.supplier_citymun);
        },
        deleteSupplier(supplier_id, supplier_name) {
            if (confirm('Do you wat to delete ' + supplier_name)) {
                const axios = require("axios");
                axios
                    .put("/api/deleteSupplier/", {'id' : supplier_id})
                    .then(() => {
                        this.fetchSuppliers(this.page);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                return false;
            }
        }
    }
}
</script>