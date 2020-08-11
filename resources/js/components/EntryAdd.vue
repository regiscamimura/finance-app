<template>
    <div>
        <b-modal
            id="entry-add-modal"
            title="Add Balance Entry"
            v-model="entry_add_show"
            size="lg"
        >
            <b-form ref="entry_add_form" @submit.stop.prevent="save">
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
            </b-form>

            <template v-slot:modal-footer="{ cancel }">
                <b-button
                    variant="tertiary p-3 px-4 text-fade font-weight-bold"
                    @click="cancel()"
                    >CANCEL</b-button
                >

                <b-button
                    variant="primary font-weight-bold p-3 px-4"
                    @click="save"
                    >SAVE ENTRY</b-button
                >
            </template>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            'rec': {
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
            'submited': false
        }
    },
    computed: {
        entry_add_show: {
            get() {
                return this.$store.state.entry_add_show;
            },
            set(value) {
                this.$store.commit("setState", ["entry_add_show", value]);
            }
        },
        entries: {
            get() { return this.$store.state.entries; },
            set(value) {
                this.$store.commit('setState', ['entries', value]);
            }
        },
        loading: {
            get() { return this.$store.state.loading },
            set(value) {
                this.$store.commit('setState', ['loading', value]);
            }
        },
        refresh: {
            get() { return this.$store.state.refresh },
            set(value) {
                this.$store.commit('setState', ['refresh', value]);
            }
        }
    },
    watch: {
        'rec': {
            handler() {
                this.validate();

            },
            deep: true
        }
    },
    methods: {
        save() {
            this.submited = true;
            if (!this.validate()) return;

            this.loading = true;
            let data = {};
            for (let field in this.rec) {
                data[field] = this.rec[field].value;
            }

            this.refresh = false;
            this.submited = false;

            this.axios
                .post(this.$config.apiURL + 'balance-entry', data)
                .then(response => {

                    this.refresh = true;
                    for (let field in this.rec) {
                        this.rec[field].value = "";
                    }
                    this.entry_add_show = false;

                })
                .catch(error => console.log(error))
                .finally(() => this.loading = false)


        },
        validate() {

            if (!this.submited) return;

            this.validation_result = true;
            for (let field in this.rec) {
                this.rec[field].valid = !String(this.rec[field].value).trim() || parseFloat(this.rec[field].value) == 0 ? false: true;
                if (!this.rec[field].valid) this.validation_result = false;
            }

            return this.validation_result;
        }
    }
};
</script>
