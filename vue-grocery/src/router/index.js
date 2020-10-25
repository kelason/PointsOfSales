import Vue from "vue";
import VueRouter from "vue-router";
import VueSession from "vue-session";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Menu from "../views/Menu.vue";
import Payment from "../views/Payment.vue";
import Product from "../views/Product.vue";
import Category from "../views/Category.vue";
import Purchase from "../views/Purchase.vue";
import PurchaseProduct from "../views/PurchaseProduct.vue";
import Retail from "../views/Retail.vue";
import Inventory from "../views/Inventory.vue";

Vue.use(VueRouter);
Vue.use(VueSession);

const session = Vue.prototype.$session;

const routes = [
  {
    path: "/",
    name: "HOME",
    component: Home
  },
  {
    path: "/login",
    name: "Login",
    component: Login
  },
  {
    path: "/terminal",
    name: "POS TERMINAL",
    component: Menu
  },
  {
    path: "/terminal/payment",
    name: "PAYMENT",
    component: Payment
  },
  {
    path: "/product",
    name: "PRODUCTS",
    component: Product
  },
  {
    path: "/category",
    name: "CATEGORIES",
    component: Category
  },
  {
    path: "/purchase",
    name: "PURCHASES",
    component: Purchase
  },
  {
    path: "/purchase-product",
    name: "PURCHASE PRODUCTS",
    component: PurchaseProduct
  },
  {
    path: "/retail",
    name: "PRODUCTION",
    component: Retail
  },
  {
    path: "/inventory",
    name: "INVENTORY",
    component: Inventory
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
