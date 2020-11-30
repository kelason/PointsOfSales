import Vue from "vue";
import VueRouter from "vue-router";
import VueSession from "vue-session";
import VueCharts from 'vue-chartjs';
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Menu from "../views/Menu.vue";
import Payment from "../views/Payment.vue";
import Product from "../views/Product.vue";
import Category from "../views/Category.vue";
import Purchase from "../views/Purchase.vue";
import PurchaseProduct from "../views/PurchaseProduct.vue";
import Inventory from "../views/Inventory.vue";
import SalesReport from "../views/SalesReport.vue";
import CashDrop from "../views/CashDrop.vue";
import Expense from "../views/Expense.vue";
import ExpenseDetails from "../views/ExpenseDetails.vue";
import Spoilage from "../views/Spoilage.vue";
import Settings from "../views/Settings.vue";
import Supplier from "../views/Supplier.vue";
import PrintInventory from "../views/PrintInventory.vue";
import PrintSales from "../views/PrintSales.vue";
import PrintSalesReceipt from "../views/PrintSalesReceipt.vue";
import PrintCashDrop from "../views/PrintCashDrop.vue";
import PrintSpoilage from "../views/PrintSpoilage.vue";
import Change from "../views/Change.vue";

Vue.use(VueRouter);
Vue.use(VueSession);
Vue.use(VueCharts);

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
    path: "/inventory",
    name: "INVENTORY",
    component: Inventory
  },
  {
    path: "/sales-report",
    name: "SALES REPORT",
    component: SalesReport
  },
  {
    path: "/cash-drop",
    name: "CASH DROP",
    component: CashDrop
  },
  {
    path: "/expense",
    name: "CASH EXPENSE",
    component: Expense
  },
  {
    path: "/expense-details",
    name: "EXPENSE DETAILS",
    component: ExpenseDetails
  },
  {
    path: "/spoilage",
    name: "SPOILAGE",
    component: Spoilage
  },
  {
    path: "/settings",
    name: "SETTINGS",
    component: Settings
  },
  {
    path: "/supplier",
    name: "SUPPLIERS",
    component: Supplier
  },
  {
    path: "/print/inventory",
    name: "Print Inventory",
    component: PrintInventory
  },
  {
    path: "/print/sales",
    name: "Print Sales",
    component: PrintSales
  },
  {
    path: "/print/sales-receipt",
    name: "SALES RECEIPT",
    component: PrintSalesReceipt
  },
  {
    path: "/print/cash-drop",
    name: "REMITTANCE",
    component: PrintCashDrop
  },
  {
    path: "/print/spoilage",
    name: "PSPOILAGE",
    component: PrintSpoilage
  },
  {
    path: "/change",
    name: "CHANGE",
    component: Change
  }
];

const router = new VueRouter({
  mode: "history",
  base: "/grocery/",
  routes
});
router.beforeEach((to,from,next) => {
  if (to.name !== 'Login' && !session.exists()) next({ name: 'Login' })
  else next()
});
export default router;
