<template>
    <div class="row">
        <div class="col-sm-10 mx-auto">
            <div class="card fullheight rounded-0">
                <div class="card-header bg-gradient">
                    <div class="col-1 offset-11">
                        <i class="fas fa-plus-circle fa-2x ml-5" title="Add Products" @click="toggleModal(), edit = false;" style="cursor: pointer;"></i>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row item-bottom pl-4 pr-4 pb-4">
                        <div class="col-4">
                            <button class="btn btn-sm btn-primary" @click="back()"><span class="float-left ml-2"><i class="fas fa-arrow-left"></i> Back</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" :class="{show, 'd-block': active}" role="dialog" id="addProduct">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient">
                        <h5 class="modal-title">Cash Count</h5>
                        <button type="button" class="close" @click="toggleModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tbody class="border-top-0" v-for="denom in denoms" :key="denom.id">
                                <tr>
                                    <td>&#8369; {{ denom.amount }}</td>
                                    <td class="pl-3 pr-3 pb-1"><input type="number" v-model="money[denom.id]" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0"></td>
                                    <td class="pl-3 pr-3"><input type="number" :value="(isNaN(money[denom.id] * denom.amount)) ? 0 : money[denom.id] * denom.amount" class="form-control form-control-sm rounded-0 border-top-0 border-left-0 border-right-0" disabled></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Cash Count :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Expense :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">Total Cash Sale :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totAmount }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold text-right">OVERAGE / (SHORTAGE) :</td>
                                    <td class="pl-2 pr-2">&#8369; {{ totAmount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Save changes</button>
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
            denoms: [
                {
                    id: 0,
                    amount: 1000
                },
                {
                    id: 1,
                    amount: 500
                },
                {
                    id: 2,
                    amount: 200
                },
                {
                    id: 3,
                    amount: 100
                },
                {
                    id: 4,
                    amount: 50
                },
                {
                    id: 5,
                    amount: 20
                },
                {
                    id: 6,
                    amount: 10
                },
                {
                    id: 7,
                    amount: 5
                },
                {
                    id: 8,
                    amount: 1
                },
                {
                    id: 9,
                    amount: 0.25
                }
            ],
            money: [],
            active: false,
            show: false
        }
    },
    created () {
    },
    methods: {
        back() {
            this.$router.push("/");
        },
        toggleModal() {
            const body = document.querySelector("body");
            this.active = !this.active;
            this.active
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.show = !this.show), 10);
        },
        fetch() {
        }
    },
    computed: {
        totAmount: function() {
            var sum=0;
            this.denoms.forEach(element => {
                sum += (isNaN(element.amount * this.money[element.id])) ? 0 : element.amount * this.money[element.id]
            });
            return (sum).toFixed(2);
        }
    }
}
</script>