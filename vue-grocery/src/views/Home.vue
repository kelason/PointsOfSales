<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/terminal">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-cash-register fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Cash Register</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/product">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-shopping-basket fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Products</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/category">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-tags fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Categories</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/purchase">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-shopping-bag fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Purchase</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/sales-report">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-store fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Sales Report</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/inventory">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-boxes fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Inventory</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/cash-drop">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-file-invoice-dollar fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Cash Drop</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/expense">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-money-check-alt fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Expense</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/spoilage">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-exchange-alt fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Spoilages</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/supplier">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-truck fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Supplier</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <router-link to="/settings">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-cog fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Settings</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <a @click="logout">
                <div class="card bg-dark text-white" style="cursor: pointer;">
                    <div class="card-content p-2">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="align-self-center">
                                    <i class="fas fa-sign-out-alt fa-5x float-left"></i>
                                </div>
                                <div class="media-body align-self-end text-right">
                                    <h3>Logout</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>
<script>
export default {
  created () {
  },
  data() {
    return {

    }
  },
  methods: {
    logout() {
        if (this.$session.exists()) {
            if (confirm("Continue to logout?")) {
                this.$session.destroy();
                this.$router.push("/login");
            } else {
                return false;
            }
        }
    }
  }
}
</script>