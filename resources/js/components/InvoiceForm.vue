<template>
    <form id="createInvoice" method="POST" action="/invoices" @submit.prevent="onSubmit">
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
                                    v-model="invoice.selectedCompany"
                                    @blur="$v.invoice.selectedCompany.$touch()"
                                    @sendinvoicenotes="getInvoiceNotes"
                            >
                            </company-select>
                            <template v-if="$v.invoice.selectedCompany.$error">
                                <small v-if="$v.invoice.selectedCompany.required"
                                >Please select company</small>
                                <small v-if="!$v.invoice.selectedCompany.integer">Company does not exist</small>
                            </template>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content">
                        <div class="col-md-8">
                            <customer-select
                                    :customers="customers"
                                    @blur="$v.invoice.selectedCustomer.$touch()"
                                    :bus="eventBus"
                                    v-model="invoice.selectedCustomer">
                            </customer-select>
                            <template v-if="$v.invoice.selectedCustomer.$error">

                                <small v-if="!$v.invoice.selectedCustomer.allInputsFilled">
                                    Please fill all inputs
                                </small>
                                <small v-else-if="!$v.invoice.selectedCustomer.isCorrectType">Sorry but you have
                                    choosen wrong data</small>
                                <small v-else-if="!$v.invoice.selectedCustomer.required"
                                >Please select customer</small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Logo part--}} -->
            <div class="invoice-box invoice-logo-box mt-3">
                <div class="company-logo">
                    <a href="#">
                        <img src="http://placehold.it/350x100?text=Logo" alt="">
                    </a>
                </div>
                <div class="mt-1">
                    <button class="btn btn-danger ml-auto">Delete Logo</button>
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
                        <div class="form-group">
                            <input type="number" name="invoice_number" id="invoice_number"
                                   class="form-control"
                                   @blur="$v.invoice.selectedInvoiceNumber.$touch()"
                                   v-model="invoice.selectedInvoiceNumber"
                            >
                            <template v-if="$v.invoice.selectedInvoiceNumber.$error">
                                <small v-if="!$v.invoice.selectedInvoiceNumber.required"
                                >You must fill number field</small>
                                <small v-if="!$v.invoice.selectedInvoiceNumber.integer">It should be numeric
                                    type</small>
                            </template>
                        </div>
                    </div>
                </div>
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
                                <small v-if="!$v.invoice.selectedDateFrom.required"
                                >Please fill the date field</small>
                            </template>
                        </div>
                    </div>
                </div>
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
                                <small v-if="!$v.invoice.selectedDateTo.required"
                                >Please fill the date field</small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <!-- {{--Items part--}} -->

            <!--  <div class="invoice-box invoice-item-box">
                      @include('invoices.items_table')
                  </div>        -->

            <div class="invoice-box invoice-item-box">
                <items-table @validation-status-changed="v => isTableInvalid = v"
                             :bus="eventBus"
                             :items="invoice.selectedItems"></items-table>
                <p v-if="isTableInvalid" class="error">Please correct table data</p>
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
                    <span>0</span>
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
    import { required, integer } from 'vuelidate/lib/validators'
    /*import Items from './ItemsTable.vue'*/
    export default {
        /* comments: {
             'items-table': Items
         },*/
        props: {
            invoiceNumber: {
                type: Number,
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
        },
        data() {
            return {
                isTableInvalid: false,
                eventBus: new Vue(),
                invoice: {
                    selectedCompany: NaN,
                    selectedCustomer: {},
                    //selectedFile: null,
                    selectedDateFrom: new Date().toISOString().slice(0,10),
                    selectedDateTo: new Date().toISOString().slice(0,10),
                    selectedInvoiceNumber: this.invoiceNumber,
                    selectedItems: [
                        {
                            id: 1,
                            item: null,
                            description: null,
                            quantity: 1,
                            unitprice: 1
                        }
                    ]
                },
                notes: ''
            }
        },
        validations: {
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
                    required,
                    integer
                }
            }
        },
        watch: {
            '$v.$error'(v) {
                //debugger
                if ( v === true) {
                    this.sendButton.disabled = true
                } else {
                    if (this.isTableInvalid === false) {
                        this.sendButton.disabled = false
                    }
                }
            },
            isTableInvalid(v) {
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
            resetInvoice() {
                this.invoice.selectedCompany = NaN
                this.invoice.selectedCustomer = {}
                //this.invoice.selectedFile = null
                this.invoice.selectedDateFrom = new Date().toISOString().slice(0, 10)
                this.invoice.selectedDateTo = new Date().toISOString().slice(0, 10)
                this.invoice.selectedInvoiceNumber = this.invoiceNumber + 1
                this.invoice.selectedItems = [
                    {
                        id: 1,
                        description: null,
                        item: null,
                        quantity: 1,
                        unitprice: 1
                    }
                ]
                this.isTableInvalid = false
                this.isTableInvalid = false
            },
            async onSubmit() {
                try {
                    this.$v.$touch();
                    this.eventBus.$emit('touch', true)
                    this.eventBus.$emit('reset', true)
                    if (!this.$v.$error && !this.isTableInvalid) {
                        console.log(JSON.stringify(this.invoice));
                        await axios.post('/invoices', this.invoice)
                        this.resetInvoice()
                        this.eventBus.$emit('update', true)
                        this.$v.$reset()
                        console.log('resetting')
                    }
                } catch(e) {
                    // this.resetInvoice()
                    // this.$v.$reset()
                }
            },
            getInvoiceNotes(variable){
                this.notes = variable;
            }
        },
        computed: {
            currentDate() {
                return new Date().toISOString().slice(0,10);
            },
            total() {
                return this.invoice.selectedItems.reduce((acc, curr) => acc+curr.unitprice*curr.quantity, 0)
            }
        }
    }
    function isObject(v) {
        return  typeof v === 'object' && v !== null
    }
</script>