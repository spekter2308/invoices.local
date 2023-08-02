<template>
    <div class="mt-3 invoice-create-body">
        <div class="wrapper-invoice-create">

            <!-- {{--Company and Customer part--}} -->
            <div class="invoice-box invoice-from-to-customer-box">
                <div class="container">
                    <div class="row justify-content">
                        <div class="col-md-12">
                            <div class="company-data-show">
                                <span><a :href="'/invoices?bycompany=' + invoice.company_id">{{ invoice.company.name }}</a></span>
                                <span v-html="replaceCompanyAddress"></span>
                            </div>
                            <div v-if="invoice.status == 'Paid'" class="paid"></div>
                            <div class="customer-data-show">
                                <div><a :href="'/invoices?byuser=' + invoice.customer_id ">{{ invoice.customer.name }}</a></div>
                                <span v-html="replaceCustomerAddress"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Logo part--}} -->
            <div class="invoice-box invoice-logo-box mt-3">
                <div v-if="invoice.company.logo_img" class="company-logo">
                    <img :src="'/upload/company/' + invoice.company.logo_img" class="logo">
                    <h3 style="letter-spacing: 6px; margin-top: 20px;">{{ $t("message.invoice") }}</h3>
                </div>

                <div v-else>
                    <h1 style="letter-spacing: 6px; float: right;">{{ $t("message.invoice") }}</h1>
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
                <div class="row level text-right">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">{{ $t("message.invoice_number") }}</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span>{{ invoice.number }}</span>
                        </div>
                    </div>
                </div>
                <!--Invoice Date-->
                <div class="row level text-right">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">{{ $t("message.invoice_date") }}</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span>{{ invoice_date}}</span>
                        </div>
                    </div>
                </div>
                <!--Due Date-->
                <div class="row level text-right">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">{{ $t("message.due_date") }}</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span>{{ due_date }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Items part--}} -->
            <div class="invoice-box invoice-item-box">
                <div class="items-wrapper">

                    <div :class="{'items-table-header-show-with-tax': settings.show_tax, 'items-table-header-show': !settings.show_tax}" style="margin: 0;">
                        <div class="item-name">
                            {{ $t("message.item") }}
                        </div>
                        <div class="item-description">
                            {{ $t("message.description") }}
                        </div>
                        <div class="item-unit-price" style="white-space: nowrap; padding-left: 10px">
                            {{ $t("message.unit_price") }}
                        </div>
                        <div class="item-quantity" style="padding-left: 10px">
                            {{ $t("message.quantity") }}
                        </div>
                        <div v-if="settings.show_tax" class="item-tax" style="padding-left: 20px;">
                            {{ $t("message.tax") }}
                        </div>
                        <div class="item-amount" >
                            {{ $t("message.amount") }}
                        </div>
                    </div>

                    <div class="items-table-body">
                        <template v-for="(item, index) in items">
                            <div :class="{'items-table-row-show': !settings.show_tax, 'items-table-row-show-with-tax': settings.show_tax}">
                                <div class="item-name">
                                    <div class="form-group-table">
                                        {{ item.item }}
                                    </div>
                                </div>
                                <div class="item-description">
                                    <div class="form-group-table">
                                        {{ item.description }}
                                    </div>
                                </div>
                                <div class="item-unit-price">
                                    <div class="form-group-table">
                                        {{ unitPrice(item.unitprice) }}
                                    </div>
                                </div>
                                <div class="item-quantity">
                                    <div class="form-group-table">
                                        {{ quantityRound(item.quantity) }}
                                    </div>
                                </div>
                                <div v-if="settings.show_tax" class="item-tax">
                                    <div class="form-group-table">
                                        {{ item.itemtax }}
                                    </div>
                                </div>
                                <div class="item-total">
                                    <div class="form-group-table">
                                        {{ itemsTotal(item.quantity, item.unitprice) }}
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div v-if="invoice.settings.show_payment" class="show-payment-for-invoice" style="margin: 3px;">
                            <div class="show-payment-head">
                                Payment
                            </div>
                            <div class="show-payment-body">
                                {{ invoice.amount_paid }}
                            </div>
                        </div>
                        <div class="invoice-table-row-notes">
                            <div class="form-group">
                                <span style="text-decoration: underline">NOTES</span>:
                                <span v-html="getNotes"></span>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-table-result">
                        <div class="invoice-empty">
                        </div>

                        <div class="invoice-total">
                            <div class="level mt-2">
                                <h6 class="flex" >{{ $t("message.subtotal") }}</h6>
                                <span>{{ subtotal + ' ' + invoice.settings.currency }}</span>
                            </div>
                            <div class="level mt-2 with-tax" v-if="settings.show_tax">
                                <h6 class="flex" >+ Tax</h6>
                                <span>{{ withTax + ' ' + invoice.settings.currency }}</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h6 class="flex" >{{ $t("message.total")}}</h6>
                                <span>{{ total + ' ' + invoice.settings.currency }}</span>
                            </div>
                            <div class="level">
                                <h6 class="flex" >{{ $t("message.amount_paid")}}</h6>
                                <span>{{  invoice.amount_paid + ' ' + invoice.settings.currency}}</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h6 class="flex" >{{ $t("message.balance_due")}}</h6>
                                <span>{{ balance + ' ' + invoice.settings.currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InvoiceShow",

        props: {
            defaultOptions: {
                type: Object,
                required: true
            },
            currentInvoice: {
                type: [Object],
                required: true
            },
            invoiceItems: {
                type: [Array],
                required: true
            },
            dateTo: {
                type: String,
                required: true,
            },
            dateFrom: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                invoice: this.currentInvoice,
                settings: this.defaultOptions,
                items: this.invoiceItems,
                invoice_date: this.dateFrom,
                due_date: this.dateTo,
            }
        },
        watch: {
            'getLocale': {
                immediate: true,
                handler: function(v) {
                    this.$i18n.locale = v;
                },
            }
        },
        computed: {
            replaceCompanyAddress() {
                return this.invoice.company.address.replace(/\n/g, '<br>');
            },
            replaceCustomerAddress() {
                if (this.invoice.customer.address) {
                    return this.invoice.customer.address.replace(/\n/g, '<br>');
                } else {
                    return '';
                }
            },
            getNotes() {
                return this.invoice.invoice_notes.replace(/\n/g, '<br>');
            },
            getLocale() {
                if (this.settings.language == 'english') {
                    return 'en';
                } else if (this.settings.language == 'germany') {
                    return 'gr';
                } else {
                    return 'sp';
                }
            },
            withTax() {
                let tax = this.items.reduce((acc, curr) =>
                    acc+(curr.unitprice*curr.quantity*curr.itemtax/100), 0);
                return parseFloat(tax.toFixed(2));
            },
            subtotal() {
                return parseFloat(this.invoice.subtotal.toFixed(2));
            },
            total() {
                if (this.settings.show_tax) {
                    return parseFloat(this.invoice.total.toFixed(2));
                } else {
                    return parseFloat(this.invoice.subtotal.toFixed(2));
                }
            },
            balance() {
                if (this.settings.show_tax) {
                    return parseFloat(this.invoice.balance.toFixed(2));
                } else {
                    return parseFloat((this.invoice.subtotal - this.invoice.amount_paid).toFixed(2));
                }
            },

        },
        methods: {
            itemsTotal(quan, unit_price) {
                return parseFloat((quan * unit_price).toFixed(2));
            },
            unitPrice(price) {
                return parseFloat(price.toFixed(10));
            },
            quantityRound(quan) {
                return parseFloat(quan.toFixed(10));
            }
        }
    }
</script>

<style>

</style>
