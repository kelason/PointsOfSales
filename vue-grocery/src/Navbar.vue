<template>
  <div id="app" class="container-fluid" v-if="currentRouteName != 'PURCHASE PRODUCTS'">
    <nav class="navbar navbar-expand-md navbar-light bg-transparent" v-if="$session.exists()">
      <router-link class="navbar-brand sansita" to="/">Generic POS</router-link>
      <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2"></div>
      <div class="mr-5 order-0">
        <a class="navbar-brand mr-5" href="#">{{ currentRouteName }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse w-100 order-2 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              {{someDate}}
            </li>
        </ul>
      </div>
    </nav>
    <div class="container-full">
        <router-view />
    </div>
  </div>

  <div id="app" v-else>
    <div class="container-full">
        <router-view />
    </div>
  </div>
</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
      someDate: new Date()
    }
  },
  computed: {
    currentRouteName() {
      return this.$route.name;
    }
  },
  created(){
    setInterval(() => {
      this.someDate = moment().format("dddd, MMMM Do YYYY, h:mm:ss a");
    }, 1000);
  },
  methods: {
    
  }
}
</script>