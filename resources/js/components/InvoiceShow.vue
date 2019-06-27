<template>
    <div class="mt-3 invoice-create-body">
        <div class="wrapper-invoice-create">

            <!-- {{--Company and Customer part--}} -->
            <div class="invoice-box invoice-from-to-customer-box">
                <div class="container">
                    <div class="row justify-content">
                        <div class="col-md-12">
                            <div class="company-data-show">
                                <span><a href="/invoices?bycompany="{{ invoice.company_id }}>{{ invoice.company_name }}</a></span>
                                <!--<span>{!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->address))  !!}</span>-->
                            </div>
                            <div v-if="invoice.status == 'Paid'" class="paid"></div>
                            <div class="customer-data-show">
                                <div><a href="/invoices?byuser="{{ invoice.customer_name }}>{{ invoice.customer_name }}</a></div>
                                <!--<div>{!! nl2br(str_replace(" ", " &nbsp;", $invoice->customer->address))  !!}</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Logo part--}} -->
            <div class="invoice-box invoice-logo-box">
                <div class="company-logo">
                    <img v-if="invoice.company.logo_img" src="/upload/company/"{{invoice.company_logo_img}} class="logo">
                </div>
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
                            <span>{{ invoice.invoice_date }}</span>
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
                            <span>{{ invoice.due_date }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- {{--Items part--}} -->
            <div class="invoice-box invoice-item-box">
                <div class="items-wrapper">

                    <div v-if="this.tax" class="items-table-header-with-tax">
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
                        <div v-if="this.tax" class="item-tax" style="padding-left: 20px;">
                            {{ $t("message.tax") }}
                        </div>
                        <div class="item-amount" >
                            {{ $t("message.amount") }}
                        </div>
                    </div>

                    <div v-else class="items-table-header">

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
                        <div class="item-amount" >
                            {{ $t("message.amount") }}
                        </div>
                    </div>

                    <div class="items-table-body">
                        <template v-for="(el, index) in items">
                            <div class="items-table-row-show">
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
                                        {{ item.unitprice }}
                                    </div>
                                </div>
                                <div class="item-quantity">
                                    <div class="form-group-table">
                                        {{ item.quantity }}
                                    </div>
                                </div>
                                <div class="item-total">
                                    <div class="form-group-table">
                                        {{ item.unitprice * item.quantity }}
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
                                {{ invoice.invoice_notes}}
                                <!--<p> <span style="text-decoration: underline">NOTES</span>: {!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->invoice_notes))  !!}</p>-->
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
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h6 class="flex" >Total</h6>
                                <span>{{ $total . ' ' . $invoice->settings->currency }}</span>
                            </div>
                            <div class="level">
                                <h6 class="flex" >Amount Paid</h6>
                                <span>{{$invoice->amount_paid . ' ' . $invoice->settings->currency}}</span>
                            </div>
                            <div class="border-top pb-2"></div>
                            <div class="level">
                                <h6 class="flex" >Balance Due</h6>
                                <span>{{ $balance . ' ' . $invoice->settings->currency }}</span>
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
        name: "InvoiceShow"
    }
</script>

<style>

</style>