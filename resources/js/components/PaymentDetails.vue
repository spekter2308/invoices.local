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
                                </b-col>
                                <b-col col md="2">
                                    <div>
                                        <label>Amount</label>
                                    </div>
                                    <b-input v-model="dataForm.amount" name="amount" type="number" step="0.01"></b-input>
                                    <input type="hidden" name="_token" :value="token_csrf">
                                </b-col>
                                <b-col col md="2">
                                    <div>
                                        <label>Receiving Account</label>
                                    </div>
                                    <b-form-select name="receiving_account" :options="setReceivingAccount"
                                                   v-model="dataForm.receiving_account"></b-form-select>
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
                                    <b-button class="btn btn-primary" type="submit" variant="primary">Save</b-button>

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
        methods: {
            async onSubmit() {
                try {
                    console.log(JSON.stringify(this.dataForm));
                        await axios.post('/invoices/record-payment/save/' + this.invoice.id, this.dataForm).then(response => {
                            this.id = response.data.id
                        });;

                    location.href = '/invoices/' + this.id;
                } catch(e) {
                    // this.resetInvoice()
                    // this.$v.$reset()
                    console.log('some error')
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
</style>