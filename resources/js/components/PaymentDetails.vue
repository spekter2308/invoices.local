<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="level">
                    <b-container>
                        <form @submit.prevent="onSubmit">

                            <b-row>
                                <b-col col md="2">
                                    <div>
                                        <label>Date</label>
                                    </div>
                                    <datapicker v-model="dataForm.date" format="yyyy-MM-dd" name="date" class="datapicker"></datapicker>
                                    <template v-if="$v.dataForm.$error">
                                        <p class="error-control" v-if="!$v.dataForm.date.required">
                                            You must select a date
                                        </p>
                                    </template>
                                </b-col>
                                <b-col col md="2">
                                    <div>
                                        <label>Amount</label>
                                    </div>
                                    <b-input v-model.number="dataForm.amount" name="amount" type="number" step="0.01"></b-input>
                                    <input type="hidden" name="_token" :value="token_csrf">
                                    <template v-if="$v.dataForm.$error">
                                        <p class="error-control" v-if="!$v.dataForm.amount.required">
                                            You must type amount
                                        </p>
                                    </template>
                                </b-col>
                                <b-col col md="2">
                                    <div>
                                        <label>Receiving Account</label>
                                    </div>
                                    <b-form-select name="receiving_account" :options="setReceivingAccount"
                                                   v-model="dataForm.receiving_account"></b-form-select>
                                    <template v-if="$v.dataForm.$error">
                                        <p class="error-control" v-if="!$v.dataForm.receiving_account.required">
                                            Please select receive account
                                        </p>
                                    </template>
                                </b-col>
                                <b-col col md="4">
                                    <div>
                                        <label>Notes</label>
                                    </div>
                                    <b-input name="notes" v-model="dataForm.notes" type="text"></b-input>
                                </b-col>
                                <b-col col md="2">
                                    <div>
                                        <label><br></label>
                                    </div>
                                    <b-button
                                            :disabled="$v.$error || spinnerVisible"
                                            class="btn btn-primary d-flex align-items-center"
                                            type="submit"
                                            variant="primary"
                                    >Save <div v-if="spinnerVisible" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div></b-button>
                                </b-col>
                            </b-row>
                        </form>
                    </b-container>

                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <table class="table table-bordered" striped hover >
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Receiving Account</th>
                            <th scope="col">Notes</th>
                        </tr>
                        </thead>
                    <tbody>
                        <tr v-for="history in histories">
                            <td>{{ history.created_at }}</td>
                            <td>{{ history.amount }}</td>
                            <td>{{ history.receiving_account  }}</td>
                            <td>{{ history.notes  }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
            </div>
        </div>
    </div>

</template>

<script>
    import axios from 'axios';
    import { required,  } from 'vuelidate/lib/validators'
    export default {
        props: {
            invoice: {
                type: Object,
                required: true
            },
            paymentHistory: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                spinnerVisible: false,
                histories: this.paymentHistory,
                id: null,
                dataForm: {
                    date: new Date().toISOString().slice(0,10),
                    amount: this.invoice.balance,
                    receiving_account: null,
                    notes: '',
                    invoice_id: this.invoice.id,
                },
                token_csrf: window.Laravel.csrfToken
            }
        },
        validations: {
            dataForm: {
                date: {
                    required
                },
                amount: {
                    required
                },
                receiving_account: {
                    required
                }
            }
        },
        methods: {
            async onSubmit() {
                try {
                    this.$v.$touch()
                    if (this.$v.$error) return
                    this.spinnerVisible = true
                    console.log(JSON.stringify(this.dataForm));
                        await axios.post('/invoices/record-payment/save/' + this.invoice.id, this.dataForm).then(response => {
                            this.id = response.data.id
                        });;

                    location.href = '/invoices/' + this.id;
                    this.spinnerVisible = false
                } catch(e) {
                    // this.resetInvoice()
                    // this.$v.$reset()
                    console.log('some error')
                    this.spinnerVisible = false
                }
            },
        },
        computed: {
           setReceivingAccount() {
                return [
                    {value: 'cash', text: 'Cash'},
                    {value: 'credit', text: 'Credit: ' + this.invoice.customer.name},
                    {value: 'new_account', text: 'New Account'}
                ];
            }
        }
    }
</script>

<style scoped lang="scss">
    .card {
        margin-top: 20px;
    }
    label {
        white-space: nowrap;
    }
</style>