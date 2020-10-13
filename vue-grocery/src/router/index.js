import Vue from "vue";
import VueRouter from "vue-router";
import VueSession from "vue-session";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Menu from "../views/Menu.vue";

Vue.use(VueRouter);
Vue.use(VueSession);

const session = Vue.prototype.$session;

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home
  },
  {
    path: "/login",
    name: "Login",
    component: Login
  },
  {
    path: "/terminal",
    name: "Menu",
    component: Menu
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});
router.beforeEach((to,from,next) => {
  if (to.name !== 'Login' && !session.exists()) next({ name: 'Login' })
  else next()
});
export default router;
