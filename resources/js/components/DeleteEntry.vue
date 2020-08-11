<template>
    <div>
        <b-modal
            id="entry-delete-modal"
            title="Delete Balance Entry"
            v-model="entry_delete_show"
            size="lg"
        >

            <b-row class="py-5">
                <b-col>
                    <label for="entry-delete-label" class="text-fade mb-1 font-weight-bold small" >LABEL</label>
                    <b-input id="entry-delete-label" size="lg" v-model="rec.label.value" disabled></b-input>
                </b-col>
                <b-col cols="5">
                    <label for="entry-delete-date" class="text-fade mb-1 font-weight-bold small" >DATE</label>
                    <b-input id="entry-delete-date" size="lg" type="datetime-local" disabled v-model="rec.date.value"></b-input>
                </b-col>
                <b-col cols="3">
                    <label for="entry-delete-amount" class="text-fade mb-1 font-weight-bold small" >AMOUNT</label>
                    <money v-model="rec.amount.value" class="form-control form-control-lg" disabled id="entry-delete-amount"></money>
                </b-col>
            </b-row>


            <template v-slot:modal-footer="{ cancel }">
                <b-button
                    variant="tertiary p-3 px-4 text-fade font-weight-bold"
                    @click="cancel()"
                    >CANCEL</b-button
                >

                <b-button
                    variant="danger font-weight-bold p-3 px-4"
                    @click="deleteEntry"
                    >Delete ENTRY</b-button
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
        entry_delete_show: {
            get() {
                return this.$store.state.entry_delete_show;
            },
            set(value) {
                this.$store.commit("setState", ["entry_delete_show", value]);
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
    methods: {
        deleteEntry() {
            this.refresh = false;
            this.loading = true;
            this.axios
                .delete(this.$config.apiURL + 'balance-entry/' + entry.id)
                .then(response => {
                    this.refresh = true;
                    this.loading = false;
                });

        }
    }
};
</script>
