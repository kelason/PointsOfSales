<template>
    <div class="row">
        <div class="col-sm-2">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient text-white rounded-0">
                    Menus
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2" v-for="(menu, index) in menus" :value="menu.id" :key="menu.id">
                            <button class="btn btn-block btn-outline-dark rounded-0" :class="{'active' : (selectedMenu !== null) ? menu.id == selectedMenu : index===0}" @click="selectedMenu = menu.id, headerName=menu.menu_name">{{ menu.menu_name }}</button>
                        </div>
                    </div>
                    <button class="btn btn-primary text-white btn-block item-bottom btn-lg rounded-0" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient text-white rounded-0">
                    {{ headerName }} 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12" v-if="selectedMenu === null || selectedMenu == 1">
                            <monthly-income />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MonthlyIncome from '../views/Sample.vue'
export default {
    components: { MonthlyIncome },
    data () {
        return {
            menus: [
                {
                    id: 1,
                    menu_name: "Dasboard"
                },
                {
                    id: 2,
                    menu_name: "Product Sales"
                },
                {
                    id: 3,
                    menu_name: "X Read"
                },
                {
                    id: 4,
                    menu_name: "Z Read"
                }
            ],
            headerName: "Dasboard",
            selectedMenu: null,
        }
    },
    created() {
    },
    mounted () {
      //this.renderChart(this.datacollection, this.chartOptions)
    },
    methods: {
        back() {
            this.$router.push("/");
        }
    }
}
</script>