<template>
    <div class="card">
        <div class="card-header">
            <div class="level">
                <b-container>
                    <b-form :action="action" method="post">

                        <b-row>
                            <b-col col md="2">
                                <div>
                                    <label>Date</label>
                                </div>
                                <datapicker v-model="dataForm.date" format="MM/dd/yyyy" name="date" class="datapicker"></datapicker>
                            </b-col>
                            <b-col col md="2">
                                <div>
                                    <label>Amount</label>
                                </div>
                                <b-input v-model="dataForm.amout" name="amount" type="number" step="0.01"></b-input>
                                <input type="hidden" name="_token" :value="token_csrf">
                            </b-col>
                            <b-col col md="2">
                                <div>
                                    <label>Receiving Accoun</label>
                                </div>
                                <b-form-select name="receiving_account" :options="setReceivingAccoun"
                                               v-model="dataForm.receivingAccoun"></b-form-select>
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
                                <b-button type="submit" @click="send" variant="primary">Button</b-button>

                            </b-col>
                        </b-row>
                    </b-form>
                </b-container>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            invoice: {
                required: true,
                type: String
            },
            action: {
                required: true,
                type: String
            }
        },
        data() {
            return {
                dataForm: {
                    date: '',
                    amout: 0,
                    receivingAccoun: null,
                    notes: '',
                },
                token_csrf: window.Laravel.csrfToken
            }
        },
        methods: {
            send() {
                console.log(this.dataForm);
            }
        },
        computed: {
            getInvoice() {
                return JSON.parse(this.invoice);
            },
            setReceivingAccoun() {
                return [
                    {value: 'cash', text: 'Cash'},
                    {value: 'credit', text: 'Credit: ' + this.getInvoice.customer.name},
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