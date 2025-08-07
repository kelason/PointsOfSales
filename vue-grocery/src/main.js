import Vue from "vue";
import Navbar from "./Navbar.vue";
import router from "./router";
import axios from "axios";

import "@fortawesome/fontawesome-free/css/all.min.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "./assets/css/styles.css";

import "bootstrap/dist/js/bootstrap.bundle.js";

axios.defaults.baseURL = "http://localhost:8081/grocery/public";
Vue.config.productionTip = false;

new Vue({
  router,
  render: h => h(Navbar)
}).$mount("#app");
