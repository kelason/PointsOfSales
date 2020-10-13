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
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
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
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <div class="card bg-dark text-white" style="cursor: pointer;">
                <div class="card-content p-2">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fas fa-exchange-alt fa-5x float-left"></i>
                            </div>
                            <div class="media-body align-self-end text-right">
                                <h3>Return</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <div class="card bg-dark text-white" style="cursor: pointer;">
                <div class="card-content p-2">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="far fa-id-badge fa-5x float-left"></i>
                            </div>
                            <div class="media-body align-self-end text-right">
                                <h3>Employee</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <div class="card bg-dark text-white" style="cursor: pointer;">
                <div class="card-content p-2">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fas fa-user-plus fa-5x float-left"></i>
                            </div>
                            <div class="media-body align-self-end text-right">
                                <h3>Customer</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
            <div class="card bg-dark text-white" style="cursor: pointer;">
                <div class="card-content p-2">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="align-self-center">
                                <i class="fas fa-file-invoice-dollar fa-5x float-left"></i>
                            </div>
                            <div class="media-body align-self-end text-right">
                                <h3>Expense</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 p-3">
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