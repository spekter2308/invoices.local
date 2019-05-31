<template>
    <form id="createInvoice" @submit.prevent="onSubmit">
        <!-- @csrf -->
        <slot></slot>
        <div class="wrapper-invoice-create">
            <!-- {{--Company and Customer part--}} -->
            <div class="invoice-box invoice-from-to-customer-box">
                <div class="container">
                    <div class="row justify-content">
                        <div class="col-md-8">
                            <company-select
                                    :companies="companies"
                                    :current-company="invoiceCompany"
                                    v-model="invoice.selectedCompany"
                                    @blur="$v.invoice.selectedCompany.$touch()"
                                    @sendinvoicenotes="getInvoiceNotes"
                            >
                            </company-select>
                            <template v-if="$v.invoice.selectedCompany.$error">
                                <small class="error-control" v-if="$v.invoice.selectedCompany.required">
                                    Please select company
                                </small>
                            </template>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content">
                        <div class="col-md-8">
                            <customer-select
                                    :customers="customers"
                                    :current-customer="invoiceCustomer"
                                    @blur="$v.invoice.selectedCustomer.$touch()"
                                    v-model="invoice.selectedCustomer">
                            </customer-select>
                            <template v-if="$v.invoice.selectedCustomer.$error">
                                <small class="error-control" v-if="!$v.invoice.selectedCustomer.allInputsFilled">
                                    Please select customer from list or create new
                                </small>
                                <small class="error-control" v-else-if="!$v.invoice.selectedCustomer.isCorrectType">Sorry but you have choosen wrong data</small>
                                <small class="error-control" v-else-if="!$v.invoice.selectedCustomer.required">Please select customer</small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Logo part--}} -->
            <div class="invoice-box invoice-logo-box mt-3">
                <div v-if="invoice.selectedCompany && url" class="company-logo">
                    <img :src="'/upload/company/' + url" class="logo">
                    <h3 style="letter-spacing: 6px; margin-top: 20px;">INVOICE</h3>
                </div>

                <div v-else>
                    <h1 style="letter-spacing: 6px; float: right;">INVOICE</h1>
                </div>
                <!--@change="f => invoice.selectedFile=f"
                @blur="$v.invoice.selectedFile.$touch()"-->
                <!-- <template v-if="$v.invoice.selectedFile.$error">
                     <small v-if="!$v.invoice.selectedFile.isCorrectType">
                         Sorry but you have choosen wrong data
                     </small>
                 </template>-->
            </div>

            <!-- {{--Date and Nubmer part--}} -->
            <div class="invoice-box invoice-num-date-box">
                <div class="row level">
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">Invoice #</h6>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group d-flex">
                            <input type="text" name="invoice_number" id="invoice_number"
                                   class="form-control"
                                   v-model="invoice.selectedInvoiceNumber"
                            >
                            <!--@blur="$v.invoice.selectedInvoiceNumber.$touch()"-->
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Edit
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form @submit.prevent="editNumber">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title font-weight-bold"
                                                    id="exampleModalLabel">Invoice Numbers
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>By default the invoices are numbered 00001, 00002, 00003, etc. Below you can change this. You can, of course, always manually override the system and enter any number you like when creating an invoice, though.</p>
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Prefix</label>
                                                        <input
                                                                class="form-control"
                                                                v-model="selectedNumber.prefix"
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Start #</label>
                                                        <input
                                                                class="form-control"
                                                                v-model.number="selectedNumber.start"
                                                        >
                                                        <template v-if="$v.selectedNumber.start.$invalid">
                                                            <p v-if="!$v.selectedNumber.start.minValue"
                                                               class="error-control">Start must be > 0</p>
                                                            <p v-if="!$v.selectedNumber.start.required"
                                                               class="error-control">Please fill start field</p>
                                                        </template>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Postfix</label>
                                                        <input class="form-control" v-model="selectedNumber.postfix">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Increment</label>
                                                        <input
                                                                class="form-control"
                                                                v-model.number="selectedNumber.increment"
                                                        >
                                                        <template v-if="$v.selectedNumber.increment.$invalid">
                                                            <p v-if="!$v.selectedNumber.increment.minValue"
                                                               class="error-control">Increment must be > 0</p>
                                                            <p v-if="!$v.selectedNumber.increment.required"
                                                               class="error-control">Please fill increment field</p>
                                                        </template>
                                                    </div>
                                                </div>
                                                <h6 class="font-weight-bold">Next invoice number</h6>
                                                <span>{{invoiceNum}}</span>
                                            </div>
                                            <div class="modal-footer">
                                                <button @click="resetSelectedNumber" type="button"
                                                        class="btn"
                                                        data-dismiss="modal">Close</button>
                                                <button :disabled="$v.selectedNumber.$invalid" type="button"
                                                        class="btn btn-primary"
                                                        @click="sendForm" data-dismiss="modal">Save
                                                    changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <template v-if="$v.invoice.selectedInvoiceNumber.$error">
                            <small class="error-control" v-if="!$v.invoice.selectedInvoiceNumber.required"
                            >You must fill number field</small>
                        </template>
                    </div>
                </div>
                <!--Invoice Date-->
                <div class="row level">
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">Invoice Date</h6>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="date" name="invoice_date" id="invoice_date"
                                   class="form-control"
                                   :value="currentDate"
                                   @blur="$v.invoice.selectedDateFrom.$touch()"
                                   @input="invoice.selectedDateFrom=$event.target.value"
                            >
                            <template v-if="$v.invoice.selectedDateFrom.$error">
                                <small class="error-control" v-if="!$v.invoice.selectedDateFrom.required"
                                >Please fill the date field</small>
                            </template>
                        </div>
                    </div>
                </div>
                <!--Due Date-->
                <div class="row level">
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">Due Date</h6>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="date" name="due_date" id="due_date"
                                   class="form-control"
                                   @blur="$v.invoice.selectedDateTo.$touch()"
                                   :value="currentDate"
                                   @input="invoice.selectedDateTo=$event.target.value"
                            >
                            <template v-if="$v.invoice.selectedDateTo.$error">
                                <small class="error-control" v-if="!$v.invoice.selectedDateTo.required"
                                >Please fill the date field</small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <!-- {{--Items part--}} -->

            <div class="invoice-box invoice-item-box">
                <items-table :items="invoice.selectedItems"
                             :is-dirty="$v.$dirty"
                ></items-table>
                <small v-if="isTableRowsInvalid && $v.$dirty" class="error-control-table">Please type table data</small>
            </div>

            <div class="invoice-box invoice-notes-box">
                <invoice-notes :notes="notes"></invoice-notes>
            </div>

            <div class="invoice-box invoice-total-box">
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Subtotal</h5>
                    <span>{{total}}</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Total</h5>
                    <span>{{total}}</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Amount Paid</h5>
                    <span>{{amount_paid}}</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Balance Due</h5>
                    <span>{{total}}</span>
                </div>
                <div class="border-top"></div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios'
    import { required, integer, minValue } from 'vuelidate/lib/validators'
    /*import Items from './ItemsTable.vue'*/
    export default {
        /* comments: {
             'items-table': Items
         },*/
        props: {
            invoiceId: {
                type: [Number, String],
                required: true
            },
            invoicePaid: {
                type: [Number, String],
                required: true
            },
            invoiceCustomer: {
                type: Object,
                required: true
            },
            invoiceCompany: {
                type: [Object],
                required: true
            },
            invoiceItems: {
                type: [Array],
                required: true
            },
            invoiceNumber: {
                type: String,
                required: true
            },
            formatNumber: {
                type: Object,
                required: false
            },
            invoiceNumbers: {
                type: Array,
                required: true
            },
            companies: {
                type: Array,
                required: true
            },
            customers: {
                type: Array,
                required: true
            },
            mode: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                //nextInvoiceNumberResponse: '',
                spinnerVisible: false,
                createdInvoiceId: NaN,
                isTableInvalid: true,
                invoice: {
                    selectedCompany: this.invoiceCompany.id || NaN,
                    selectedCustomer: this.invoiceCustomer.id || {},
                    //selectedFile: null,
                    selectedDateFrom: new Date().toISOString().slice(0,10),
                    selectedDateTo: new Date().toISOString().slice(0,10),
                    selectedInvoiceNumber: this.invoiceNumber,
                    selectedItems: this.invoiceItems
                },
                selectedNumber: {
                    prefix: this.formatNumber.prefix || '',
                    start: this.formatNumber.start || 0,
                    postfix: this.formatNumber.postfix || '',
                    increment: this.formatNumber.increment || 1
                },
                notes: this.invoiceCompany.invoice_notes || ''
            }
        },
        validations: {
            selectedNumber: {
                start: {
                    required,
                    minValue: minValue(0)
                },
                increment: {
                    required,
                    minValue: minValue(1)
                }
            },
            invoice: {
                selectedCompany: {
                    required,
                    integer
                },
                selectedCustomer: {
                    required,
                    isCorrectType(v) {
                        return integer(v) || isObject(v)
                    },
                    allInputsFilled(v) {
                        if (isObject(v)) {
                            return Boolean(v.name)
                        }
                        return true
                    }
                },
                /*selectedFile: {
                    isCorrectType(v) {
                        return integer(v) || isObject(v)
                    }
                },*/
                selectedDateFrom:{
                    required
                },
                selectedDateTo:{
                    required
                },
                selectedInvoiceNumber: {
                    required
                }
            }
        },
        watch: {
            '$v.$error'(v) {
                if ( v === true) {
                    this.sendButton.disabled = true
                } else {
                    if (this.isTableRowsInvalid === false || this.spinnerVisible) {
                        this.sendButton.disabled = false
                    }
                }
            },
            spinnerVisible(v) {
                if (v === true) {
                    this.sendButton.innerHTML = `Save <div disabled="true" class="spinner-border spinner-border-sm" role="status" aria-hidden="true">`
                } else {
                    this.sendButton.innerHTML = `Save <div class="" role="status" aria-hidden="true">`
                }
            },
            isTableRowsInvalid(v) {
                if ( v === true && this.$v.$dirty) {
                    this.sendButton.disabled = true
                } else {
                    if (this.$v.$error === false) {
                        this.sendButton.disabled = false
                    }
                }
            }
        },
        mounted() {
            this.sendButton = document.querySelector('.send-btn')
        },
        methods: {
            updateNextNumber() {
                return axios.get('/counters').then(response => {
                    this.nextInvoiceNumberResponse = response.data.invoiceNumber;
                    console.log(this.invoiceNumbers)
                })
            },
            resetSelectedNumber() {
                this.selectedNumber = {
                    prefix: this.formatNumber.prefix || '',
                    start: this.formatNumber.start || 0,
                    postfix: this.formatNumber.postfix || '',
                    increment: this.formatNumber.increment || 1
                }
            },
            resetInvoice() {
                this.invoice.selectedCompany = NaN
                this.invoice.selectedCustomer = {}
                //this.invoice.selectedFile = null
                this.invoice.selectedDateFrom = new Date().toISOString().slice(0, 10)
                this.invoice.selectedDateTo = new Date().toISOString().slice(0, 10)
                this.invoice.selectedInvoiceNumber = this.nextInvoiceNumberResponse;
                this.invoice.selectedItems = [
                    {
                        //id: 1,
                        description: null,
                        item: null,
                        quantity: 1,
                        unitprice: 1,
                        dirty: false,
                        correct: false
                    }
                ]
                this.notes = ''
                //this.isTableInvalid = false
            },
            async onSubmit() {
                try {
                    this.$v.$touch();
                    eventBus.$emit('touch', true)
                    if (!this.$v.$error && !this.isTableRowsInvalid) {
                        this.spinnerVisible = true
                        console.log(JSON.stringify(this.invoice));
                        if (this.mode === 'create') {
                            await axios.post('/invoices', this.invoice).then(response => {
                                this.createdInvoiceId = response.data.id
                                this.spinnerVisible = false
                            });
                            location.href = '/invoices/' + this.createdInvoiceId;
                        }
                        else if(this.mode === 'edit') {
                            console.log(this.invoiceId)
                            await axios.patch('/invoices/' + this.invoiceId, this.invoice).then(response => {
                                this.createdInvoiceId = response.data.id
                                this.spinnerVisible = false
                            });
                        }
                        location.href = '/invoices/' + this.createdInvoiceId;
                        //await this.updateNextNumber();
                        //this.resetInvoice();
                        //eventBus.$emit('update', true)
                        //this.$v.$reset()
                        //eventBus.$emit('reset', true)
                        //console.log('resetting')
                    }
                } catch(e) {
                    // this.resetInvoice()
                    // this.$v.$reset()
                    console.log('some error')
                    this.spinnerVisible = false
                }
            },
            sendForm() {
                this.editNumber();
            },
            async editNumber() {
                try {
                    console.log(JSON.stringify(this.selectedNumber));
                    await axios.patch('/counters/' + this.formatNumber.id, this.selectedNumber);
                    this.invoice.selectedInvoiceNumber = this.invoiceNum;
                    this.updateNextNumber();
                } catch (e) {
                    console.log(e)
                }
            },
            /*updateInvoiceNum() {
                //axios.get('/')
            },*/
            getInvoiceNotes(variable){
                this.notes = variable;
            },
            checkInArray(prefix, start, increment, postfix, array, old_increment) {
                let result = prefix + (parseInt(start) + parseInt(increment)) + postfix;
                if (array.includes(result)){
                    return this.checkInArray(prefix, start, (parseInt(increment) + parseInt(old_increment)), postfix,
                        array, old_increment);
                }
                return result;
            },
        },
        computed: {
            url() {
                return this.companies.find(el => el.id === this.invoice.selectedCompany).logo_img
            },
           /* urlExists() {
                return this.companies.find(el => el.id === this.invoice.selectedCompany).logo_img ? true : false;
            },*/
            isTableRowsInvalid() {
                return this.invoice.selectedItems.reduce((acc, curr) => {
                    return acc && !curr.correct
                }, true)
            },
            currentDate() {
                return new Date().toISOString().slice(0,10);
            },
            total() {
                return this.invoice.selectedItems.reduce((acc, curr) => acc+curr.unitprice*curr.quantity, 0)
            },
            balance() {
                return this.total - this.amount_paid;
            },
            amount_paid(){
                return (this.invoicePaid === '0') ? 0 : this.invoicePaid;
            },
            invoiceNum(){
                return this.checkInArray(this.selectedNumber.prefix, this.selectedNumber.start,
                    this.selectedNumber.increment, this.selectedNumber.postfix, this.invoiceNumbers,
                    this.selectedNumber.increment);
            },
        }
    }
    function isObject(v) {
        return  typeof v === 'object' && v !== null
    }
</script>