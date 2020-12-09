<template>
    <div class="fullheight">
        <div class="container h-100 text-center">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12">
                    <h1 class="display-2">&#8369; {{ $route.params.change }}</h1>
                    <router-link to="/terminal" class="pt-3"><i class="fas fa-long-arrow-alt-left"></i> Go back</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        if (!this.$route.params.change) {
            this.$router.push("/");
        }
    },
    created() {
        const axios = require("axios");
        axios
            .post("/api/printReceipt/", { order_id: this.$route.params.order_id})
            .then(() => {

            })
            .catch((error) => {
                console.log(error);
            });
    }
}
</script>