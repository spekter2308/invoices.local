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
                            >
                            </company-select>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content">
                        <div class="col-md-8">
                            <customer-select
                                    :customers="customers"
                                    v-model="invoice.selectedCustomer">
                            </customer-select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Logo part--}} -->
            <div class="invoice-box invoice-logo-box">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlFile1">Example file input</label>
                        <input
                                type="file"
                                @change="f => invoice.selectedFile=f"
                                class="form-control-file"
                                id="exampleFormControlFile1"

                        >
                    </div>
                </form>
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
                                   v-model="invoice.selectedInvoiceNumber"
                            >
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
                                   @input="invoice.selectedDateFrom=$event.target.value"
                            >
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
                                   :value="currentDate"
                                   @input="invoice.selectedDateTo=$event.target.value"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-- {{--Items part--}} -->

            <!--  <div class="invoice-box invoice-item-box">
                      @include('invoices.items_table')
                  </div>

                  <div class="invoice-box invoice-notes-box">
                      <div class="form-group">
                         <invoice-notes :notes="notes"></invoice-notes>
                      </div>
                  </div>        -->

            <div class="invoice-box invoice-total-box">
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Subtotal</h5>
                    <span>0.00</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Total</h5>
                    <span>0.00</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Amount Paid</h5>
                    <span>0.00</span>
                </div>
                <div class="border-top pb-2"></div>
                <div class="level">
                    <h5 class="flex" >Balance Due</h5>
                    <span>0.00</span>
                </div>
                <div class="border-top"></div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios'
    export default {
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
            }
        },
        data() {
            return {
                invoice: {
                    selectedCompany: this.companies[0],
                    selectedCustomer: this.customers[0],
                    selectedFile: null,
                    selectedDateFrom: null,
                    selectedDateTo: null,
                    selectedInvoiceNumber: this.invoiceNumber,
                }
            }
        },
        methods: {
            onSubmit() {
                console.log({ invoice: this.invoice });
                axios.post('/invoices', this.invoice);
            }
        },
        computed: {
            currentDate() {
                return new Date().toISOString().slice(0,10);
            }
        }
    }
</script>