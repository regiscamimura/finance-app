<template>
    <div>
        <nav class="navbar bg-light navbar-expand-md px-5 mb-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img :src="'images/logo.svg'" class="mr-3">
                <div><span class="text-dark h4">Your</span><span class="text-primary h4">Balance</span></div>
            </a>

            <div class="ml-auto d-flex align-items-center">
                <img :src="'images/alarm.svg'" class="img-fluid">
                <img :src="'images/avatar.png'" class="img-fluid mx-3 img-thumb">
                <div class="text-fade"><b>Molly Green</b></div>
            </div>


        </nav>
        <div class="bg-secondary action-bar fixed-top d-flex align-items-center">
            <div class="container d-flex">
                <div class="text-light d-flex align-items-center">
                    <h3 class="mb-0">Your Balance</h3>
                    <button class="btn btn-primary mx-3 font-weight-bold" @click.prevent="entry_add_show = true">
                        <img :src="'images/add.svg'" class="mr-3">
                        ADD ENTRY
                    </button>
                    <button class="btn btn-primary font-weight-bold" style="width: 135px;">
                        <img :src="'images/import.svg'" class="mr-3">
                        IMPORT CSV
                    </button>
                </div>
                <div class="ml-auto text-center">
                    <small class="text-fade text-uppercase font-weight-bold">Total Balance</small>
                    <div class="h2" :class="total >= 0 ? 'text-success' : 'text-danger'">{{ total | currency('$', 0) }}.<small>{{total.split('.')[1] || "00" }}</small></div>
                </div>
            </div>
        </div>

        <div class="container py-5 main">
            <div class="my-3"><Entries/></div>
        </div>


        <EntryAdd/>

        <loading :active.sync="loading"></loading>

    </div>
</template>

<style scoped lang="scss" rel="stylesheet/scss">
.navbar {
    height: 64px;
}
.action-bar {
    top: 64px;
    height: 120px;
    .btn {

        img {
            max-width: 14px;
        }
    }
}
.main {
    margin-top: 184px;
}
</style>

<script>
import style from "../sass/app.scss";
import Entries from './components/Entries';
import EntryAdd from './components/EntryAdd';

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    data() {
        return {

        }
    },
    computed: {
        total: {
            get() {
                return this.$store.state.total;
            },
            set(value) {
                this.$store.commit('setState', ['total', value]);
            }
        },
        entry_add_show: {
            get() { return this.$store.state.entry_add_show },
            set(value) {
                this.$store.commit('setState', ['entry_add_show', value]);
            }
        },
        loading: {
            get() { return this.$store.state.loading },
            set(value) {
                this.$store.commit('setState', ['loading', value]);
            }
        }
    },
    watch: {
        loading() {
            this.getTotal();
        }
    },
    mounted() {
        this.getTotal();
    },
    methods: {
        getTotal() {

            this.axios
                .get(this.$config.apiURL + 'balance-entry/total')
                .then(response => (
                    this.total = response.data.total + ""
                ))
                .catch(error => console.log(error))
                .finally(() => this.loading = false);
        }
    },
    components: {Entries, EntryAdd, Loading}
}
</script>

