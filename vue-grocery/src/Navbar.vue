<template>
  <div id="app" class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent" v-if="$session.exists()">
      <router-link class="navbar-brand" to="/">Grocery</router-link>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
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