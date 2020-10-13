<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">POS Login</h5>
                        <form class="form-signin" autocomplete="off" @submit.prevent="login">
                            <div class="form-label-group">
                                <input type="text" v-model="username" id="inputUser" class="form-control" placeholder="Username" required autofocus>
                                <label for="inputUser">Username</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" v-model="userpassword" id="inputPassword" class="form-control" placeholder="Password" required>
                                <label for="inputPassword">Password</label>
                            </div>

                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
                            <hr class="my-4">
                        </form>
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
            userpassword: null,
            username: null,
            msg: ''
        }
    },
    created () {
    },
    methods: {
        validUsername() {
            return this.username != '';
        },
        validUserpassword() {
            return this.userpassword != '';
        },
        login() {
            var app = this;
            if (app.validUsername() && app.validUserpassword()) {
                const axios = require("axios");

                axios
                    .post("/api/login.php", {
                        username: app.username,
                        userpassword: app.userpassword
                    })
                    .then(function(response) {
                        //console.log(response.data);
                        if (response.status === 200) {
                            app.msg = response.data.msg;
                            app.$session.start();
                            app.$session.set("user_id", response.data.user_id);
                            app.$router.push("/");
                            //console.log(response.data.msg);
                        }
                        setTimeout(() => {
                            app.msg = false;
                        }, 3000);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
    },
}
</script>