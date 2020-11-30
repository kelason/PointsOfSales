<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient p-0 pl-4 pr-4 pt-1">
                    <div class="row">
                        <div class="col-5">
                            <div class="form-label-group">
                                <input type="text" id="searchCategory" class="form-control form-control-sm mb-3 bg-transparent text-white rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Search Product" @keyup="searchCategory($event.target.value)">
                                <label for="searchCategory" class="text-white">Search Category</label>
                            </div>
                        </div>
                        <div class="col-1 offset-6 mt-3">
                            <i class="fas fa-plus-circle fa-2x" title="Add Categories" @click="toggleModal(), edit = false;" style="cursor: pointer;"></i>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body overflow-auto">
                    <form method="POST" enctype="multipart/form-data">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>Category Name</th>
                                    <th>Category Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="categories==''">
                                <tr class="text-center">
                                    <td colspan="9">
                                        <p class="text-danger text-center">No Records...</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr class="text-center" v-for="category in categories" :key="category.id">
                                    <td class="align-middle">{{ category.category_name }}</td>
                                    <td class="align-middle">{{ category.type_name }}</td>
                                    <td class="align-middle">{{ category.category_status }}</td>
                                    <td class="align-middle">
                                        <i class="fa fa-edit mr-2" :title="'Click to Edit ' + category.category_name" style="cursor: pointer;" @click="editCategory(category)"></i> 
                                        <i class="fa fa-trash ml-2" :title="'Click to Delete ' + category.category_name" style="cursor: pointer;" @click="deleteCategory(category.id, category.category_name)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert" v-if="msg">
                                {{msg}}
                            </div>
                        </div>
                    </div>
                    <div class="row item-bottom p-4">
                        <div class="col-6">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                        <div class="col-6">
                            <nav aria-label="Page navigation example item-bottom">
                                <ul class="pagination pagination-sm justify-content-end">
                                    <li class="page-item" :class="[{disabled: pagination.current_page == 1} || pagination == '']" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchCategories(page = page - 1)">
                                            Prev
                                        </a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                    </li>

                                    <li class="page-item" :class="[{disabled: pagination.current_page == pagination.last_page}]" style="cursor: pointer;">
                                        <a class="page-link" @click="fetchCategories(page = page + 1)">
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
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addCategory">
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
                                    <input type="text" v-model="category.category_name" id="prodname" class="form-control form-control-sm mb-3 rounded-0 border-top-0  border-left-0  border-right-0" placeholder="Category Name">
                                    <label for="prodname">Category Name</label>
                                </div>
                                <small class="text-danger font-weight-bold" v-if="errors.category_name">{{ errors.category_name }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="catid" v-model="category.category_type" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Category Type</option>
                                    <option v-for="category_type in category_types" :key="category_type.id" :value="category_type.id">{{ category_type.type_name }}</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.type_id">{{ errors.type_id }}</small>
                            </div>
                            <div class="col-6 p-2">
                                <select id="catid" v-model="category.category_status" class="form-control mb-3 rounded-0 border-top-0  border-left-0  border-right-0">
                                    <option disabled value="0">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <small class="text-danger font-weight-bold" v-if="errors.category_status">{{ errors.category_status }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addCategory">Save changes</button>
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
            categories: [],
            category_types: [],
            category: {
                category_name: '',
                category_type: 0,
                category_status: 0,
            },
            active: false,
            show: false,
            pagination: [],
            edit: false,
            errors: [],
            page: 1,
            msg: null
        }
    },
    created () {
        this.fetchCategories(this.page);
        this.fetchCategoryTypes();
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
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        fetchCategories(page) {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllCategories/?page=" + page + "&status=")
                .then(function(response) {
                    app.categories = response.data.data;
                    app.pagination = response.data.pagination;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchCategoryTypes() {
            var app = this;
            const axios = require("axios");
            
            axios
                .get("/api/getAllCategoryTypes/")
                .then(function(response) {
                    app.category_types = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        searchCategory(name) {
            var app = this;
            const axios = require("axios");
            if (app.timer) {
                clearTimeout(app.timer);
                app.timer = null;
            }
            app.timer = setTimeout(() => {
                axios
                    .get("/api/searchCategory/?page=" + app.page + "&category_name=" + name)
                    .then(function(response) {
                        app.categories = response.data.data;
                        app.pagination = response.data.pagination;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 500);
                
        },
        addCategory() {
            var app = this;
            app.errors = [];

            if (app.validCategoryName) app.errors.category_name = "Please input Category Name.";
            if (app.validCategoryType) app.errors.category_type = "Please Select Category Type.";
            if (app.validStatus) app.errors.category_status = "Please Select Category Status.";

            if(
                !app.validCategoryName &&
                !app.validCategoryType &&
                !app.validStatus 
            ) {
                const axios = require("axios");
                if(app.edit == false) {
                    axios
                        .post("/api/addCategory/", app.category)
                        .then(function(response) {
                            app.msg = response.data.msg;

                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchCategories(app.page);
                            app.toggleModal();
                            app.category = {
                                category_name: '',
                                category_type: 0,
                                category_status: 0,
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                } else {
                    axios
                        .put("/api/updateCategory/", app.category)
                        .then((response) => {
                            app.msg = response.data.msg;
                            app.edit = false;
                            setTimeout(() => {
                                app.msg = null;
                            }, 2000);
                            app.fetchCategories(app.page);
                            app.toggleModal();
                            app.category = {
                                category_name: '',
                                category_type: 0,
                                category_status: 0,
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        },
        editCategory(category) {
            let app = this;
            app.toggleModal();

            app.edit = true;

            app.category.id = category.id;
            app.category.category_name = category.category_name;
            app.category.category_type = category.category_type;
            app.category.category_status = (category.category_status == "active") ? 1 : 2;
        },
        deleteCategory(product_id, product_name) {
            if (confirm('Do you wat to delete ' + product_name)) {
                const axios = require("axios");
                axios
                    .put("/api/deleteCategory/", {'id' : product_id})
                    .then(() => {
                        this.fetchCategories(this.page);
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