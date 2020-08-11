<template>
    <div>

        <div v-if="entries.length == 0" class="alert alert-warning">No balance entries found.</div>
        <div v-for="(date, index) in dates" v-bind:key="index">
            <div class="d-flex justify-content-between align-items-center pr-4">
                <h6 class="text-fade text-uppercase font-weight-bold my-4">
                    <span v-if="moment(date).format('YYYY-MM-DD') == moment().format('YYYY-MM-DD')">Today</span>
                    <span v-else-if="index == 1">Yesterday</span>
                    <span v-else>{{ date.toLocaleDateString("en-GB", { weekday: 'short', day: 'numeric', month: 'short' }) }}</span>
                </h6>

                <h4 class="float-right" :class="total[date] > 0 ? 'text-success' : 'text-fade'" v-if="total[date]">
                    <span v-if="total[date] > 0"> + </span>
                    <span v-if="total[date] < 0"> - </span>
                    {{ Math.abs(total[date]) | currency('$', 0) }}.<small>{{(total[date] + "").split('.')[1] || "00" }}</small>

                </h4>
            </div>

            <div v-if="day_entries[date].length">
                <div class="card shadow border-0 mb-4" v-for="entry in day_entries[date]" :key="entry.id" @mouseleave="clearDelete()">

                    <div class="card-body d-flex justify-content-between align-items-center entry-item">
                        <div class="">
                            <h4>{{entry.label}}</h4>
                            <span class="text-fade">{{ moment(entry.date).format("D MMM, Y [at] HH:mm A") }}</span>
                        </div>


                        <div class="item-actions ml-auto mr-4">
                            <a href="#" class="mr-3" @click.prevent="setRec(entry)">EDIT</a>
                            <a href="#" class="mr-3 text-uppercase" @click.prevent="deleteEntry(entry)" :class="{'text-danger': delete_rec == entry.id}">
                                <span v-if="delete_rec == entry.id">I confirm I want to delete this entry</span>
                                <span v-else>DELETE</span>
                            </a>
                        </div>


                        <div class="h4" :class="entry.amount >= 0 ? 'text-success' : 'text-dark'">
                            <span v-if="entry.amount > 0"> + </span>
                            <span v-if="entry.amount < 0"> - </span>
                            {{ Math.abs(entry.amount) | currency('$', 0) }}.<small>{{(entry.amount + "").split('.')[1] || "00" }}</small>
                        </div>
                    </div>
                    <transition name="fade">
                        <b-form ref="entry_update_form" @submit.stop.prevent="updateEntry()" v-if="rec.id == entry.id">
                            <div class="border-top card-body">

                                <b-form-row class="py-5">
                                    <b-col>
                                        <label for="entry-add-label" class="text-fade mb-1 font-weight-bold small" >LABEL</label>
                                        <b-input id="entry-add-label" size="lg" v-model="rec.label.value" required :class="{'border-danger': rec.label.valid === false}"></b-input>
                                    </b-col>
                                    <b-col cols="5">
                                        <label for="entry-add-date" class="text-fade mb-1 font-weight-bold small" >DATE</label>
                                        <b-input id="entry-add-date" size="lg" type="datetime-local" v-model="rec.date.value" :class="{'border-danger': rec.date.valid === false}"></b-input>
                                    </b-col>
                                    <b-col cols="3">
                                    <label for="entry-add-amount" class="text-fade mb-1 font-weight-bold small" >AMOUNT</label>
                                        <!-- <b-input id="entry-add-amount" size="lg" v-model="rec.amount.value" type="number" step="any" required :class="{'border-danger': rec.amount.valid === false}"></b-input> -->
                                        <money v-model="rec.amount.value" class="form-control form-control-lg" required :class="{'border-danger': rec.amount.valid === false}" id="entry-add-amount"></money>
                                    </b-col>
                                </b-form-row>

                            </div>
                            <div class="card-footer bg-light text-right">
                                <b-button variant="tertiary p-3 px-4 text-fade font-weight-bold mr-3"  @click.prevent="resetRec()">CANCEL</b-button>

                                <b-button variant="primary font-weight-bold p-3 px-4" @click.prevent="updateEntry()">UPDATE ENTRY</b-button>
                            </div>
                        </b-form>
                    </transition>
                </div>
            </div>
            <div v-else>
                <div class="alert alert-warning shadow border-0 mb-4">
                    No entries found.
                </div>
            </div>


        </div>
        <nav aria-label="Page navigation" v-if="entries.length > page_length">
            <ul class="pagination justify-content-end">
                <li class="page-item" :class="{'disabled': page == 0}">
                    <a class="page-link" href="#" aria-label="Previous" @click.prevent="page = 0">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item" :class="{'disabled': page == 0}">
                    <a class="page-link" href="#" aria-label="Previous" @click.prevent="page--">
                        <span aria-hidden="true">&lsaquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>

                <li class="page-item disabled" v-if="page - 3 > 0">
                    <a class="page-link" href="#" aria-label="...">
                        <span aria-hidden="true">...</span>
                        <span class="sr-only">...</span>
                    </a>
                </li>


                <li class="page-item" v-for="i in pages_shortened" :key="i" :class="{'active': i == page}"><a class="page-link" href="#" @click.prevent="page = i">{{ i + 1 }}</a></li>


                 <li class="page-item disabled" v-if="pages.length > 5 && pages.length - 1 > page + 1 ">
                    <a class="page-link" href="#" aria-label="...">
                        <span aria-hidden="true">...</span>
                        <span class="sr-only">...</span>
                    </a>
                </li>

                <li class="page-item" :class="{'disabled': page == pages[pages.length - 1] }">
                    <a class="page-link" href="#" aria-label="Previous" @click.prevent="page++">
                        <span aria-hidden="true">&rsaquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>

                <li class="page-item" :class="{'disabled': page == pages[pages.length - 1] }">
                    <a class="page-link" href="#" aria-label="Next" @click.prevent="page = pages.length - 1">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<style scoped lang="scss" rel="stylesheet/scss">
.page-link {
    min-width: 45px;
    text-align: center;
}
.entry-item {
    &:hover {
        .item-actions {
            opacity: 1;
        }
    }
}
.item-actions {
    opacity: 0;
    transition: opacity 400ms ease;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 400ms;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>

<script>
    export default {
        props: {
            page_length: {
                Number,
                default: 100
            }
        },
        data() {
            return {
                page: 0,
                'rec': {
                    'id': null,
                    'label': {
                        value: '',
                        valid: null
                    },
                    'date': {
                        value: '',
                        valid: null
                    },
                    'amount': {
                        value: '',
                        valid: null
                    },
                },
                'validation_result': null,
                'submited': false,
                'delete_rec': false
            }
        },
        watch: {
            refresh() {
                if (this.refresh) this.loadEntries();
            }
        },
        computed: {
            refresh: {
                get() { return this.$store.state.refresh },
                set(value) {
                    this.$store.commit('setState', ['refresh', value]);
                }
            },
            entries: {
                get() { return this.$store.state.entries; },
                set(value) {
                    this.$store.commit('setState', ['entries', value]);
                }
            },
            paged_entries() {
               return this.entries.slice(this.page * this.page_length, ((this.page + 1) * this.page_length));
            },
            day_entries() {
                let entries = {};
                this.dates.forEach(date =>  {
                    entries[date] = this.paged_entries.filter(entry => {
                        return this.moment(entry.date).format('YYYY-MM-DD') == this.moment(date).format('YYYY-MM-DD')
                    });
                });

                return entries;
            },
            total() {
                let total = {};
                this.dates.forEach(date =>  {
                    total[date] = this.entries.filter(entry => {
                        return this.moment(entry.date).format('YYYY-MM-DD') == this.moment(date).format('YYYY-MM-DD')
                    }).reduce( (sum, entry) => {
                        return (parseFloat(sum) + parseFloat(entry.amount)).toFixed(2);
                    }, 0);
                });

                return total;
            },
            pages() {
                return Array.from(Array(Math.ceil(this.entries.length / this.page_length)).keys());
            },
            pages_shortened() {

                let ini = Math.max(0, this.page - 3);
                let end = ini + 5;

                if (end > this.pages.length && ini > 0) ini--;
                return this.pages.slice(ini, end)
            },
            dates() {

                let dates = [];
                if (this.paged_entries.length == 0) return dates;
                let current_date = this.page > 0 ? new Date(this.moment(this.paged_entries[0].date).format('MM/DD/YYYY')) : new Date(this.moment().format('MM/DD/YYYY'));
                // let last_date = new Date(Object.keys(this.entries).slice(-1));
                let last_date = new Date(this.moment(this.paged_entries[this.paged_entries.length - 1].date).format('MM/DD/YYYY'));
                while (current_date >= last_date ) {
                    dates.push(new Date(current_date));
                    current_date.setDate(current_date.getDate() - 1);
                }

                return dates;
            },
            loading: {
                get() { return this.$store.state.loading },
                set(value) {
                    this.$store.commit('setState', ['loading', value]);
                }
            },
        },
        mounted() {
            this.loadEntries();
        },
        methods: {
            formatDate(date) {
                return new Intl.DateTimeFormat('en-US').format(date);
            },
            loadEntries() {
                this.axios
                    .get(this.$config.apiURL + 'balance-entry/')
                    .then(response => (
                        this.entries = response.data
                    ))
                    .catch(error => console.log(error))
                    .finally(() => this.loading = false);
            },
            setRec(entry) {
                this.submited = false;
                this.validation_result = null;
                this.rec.id = entry.id;
                for (let field in this.rec) {
                    if (field == 'id') continue;
                    this.rec[field].valid = null;
                    if (field == 'date') this.rec[field].value = this.moment(entry[field]).format('yyyy-MM-DDThh:mm')
                    else this.rec[field].value = entry[field];
                }
            },
            resetRec() {
                this.submited = false;
                this.validation_result = null;
                this.rec.id = null;
                for (let field in this.rec) {
                    if (field == 'id') continue;
                    this.rec[field].valid = null;
                    this.rec[field].value = '';
                }
            },
            updateEntry() {
                this.submited = true;
                if (!this.validate()) return;

                this.loading = true;
                let data = {};
                for (let field in this.rec) {
                    if (field == 'id') continue;
                    data[field] = this.rec[field].value;
                }

                this.refresh = false;
                this.submited = false;

                this.axios
                    .put(this.$config.apiURL + 'balance-entry/' + this.rec.id, data)
                    .then(response => {
                        this.refresh = true;
                        this.resetRec();
                    })
                    .catch(error => console.log(error))
                    .finally(() => this.loading = false)
            },
            validate() {

                if (!this.submited) return;

                this.validation_result = true;
                for (let field in this.rec) {
                    if (field == 'id') continue;
                    this.rec[field].valid = !String(this.rec[field].value).trim() || parseFloat(this.rec[field].value) == 0 ? false: true;
                    if (!this.rec[field].valid) this.validation_result = false;
                }

                return this.validation_result;
            },
            clearDelete() {
                setTimeout(() => {this.delete_rec = null}, 400);
            },
            deleteEntry(entry) {
                if (this.delete_rec != entry.id) {
                    this.delete_rec = entry.id;
                    return;
                }
                this.refresh = false;
                this.axios
                    .delete(this.$config.apiURL + 'balance-entry/' + entry.id)
                    .then(response => {
                        this.refresh = true;
                    });
            }
        }
    }
</script>
