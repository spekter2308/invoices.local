<style>
    .page-break {
        /*page-break-after: always;*/
    }
</style>
<div class="page-break">
    <table cellpadding="0" cellspacing="0" style="width:100%;margin:0px;padding:0px;">
        <tbody>
        <tr>
            <td colspan="100" width="100%"
                style="text-align:left;border-left:1px solid silver;border-top:1px solid silver;border-right:2px solid silver;border-bottom:2px solid silver;padding-left:40px;padding-right:40px; padding-top:40px; padding-bottom:40px;">

                <!-- TB 322 -->
                <table border="0" cellpadding="0" cellspacing="0"
                       style="width:720px; padding-bottom:40px; padding-left:0px;margin:0px;text-align:left;vertical-align:top;">
                    <tbody>
                    <tr>
                        <td style="vertical-align:top;">
                            <!-- TB 5111 -->
                            <table border="0" cellpadding="0" cellspacing="0"
                                   style="margin:0px; padding:0px;vertical-align:top;">
                                <tbody>
                                <tr>
                                    <td style="border:0px solid blue;width:370px;padding-top:0px;padding-left:0px; vertical-align:top;font-family:verdana; font-size:13px; color:#3c3c3c;line-height:13px;">

                                        <br><span
                                                id="from_address">{{ $invoice->company->name }}<br>{{ $invoice->company->address }}</span><br><br><br><br><br><br><br><br><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="to_name"
                                        style="border:0px solid green;width:280px;padding-top:0px;padding-left:0px; vertical-align:top;font-family:verdana; font-size:13px; color:#3c3c3c;line-height:13px;">
                                        {{ $invoice->customer->name }}<br>{{ $invoice->customer->address }}
                                    </td>
                                </tr>

                                <!-- TB 5 -->
                                </tbody>
                            </table>
                        </td>


                        <td style="vertical-align:top;padding-top:0px; margin-top:0px;line-height:13px;" rowspan="2">

                            <!-- TB 5 -->
                            <table border="0" cellpadding="0" cellspacing="0"
                                   style="margin:0px; padding:0px;vertical-align:top;">
                                <tbody>
                                <tr>
                                    <td colspan="2" style="border:0px solid red; text-align:right;">

                                        <br>
                                        <p id="invoice_headline"
                                           style="vertical-align:top;margin-top:0px;margin-bottom:0px;text-align:right;letter-spacing:5px;font-size:28px;color:#808080;font-weight:bold;font-family:arial;">
                                            <img src="{{($invoice->logo_img) ? 'upload/company/' . $invoice->company->logo_img : '/img/no_img.png'}}"
                                                 alt="">
                                        </p><br><br><br><br><br><br><br><br><br><br>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="border:0px solid black; width:155px;">&nbsp;</td>

                                    <td style="margin:0px; padding:0px;">

                                        <table border="0" cellpadding="0" cellspacing="0"
                                               style="border:0px solid red;text-align:right;margin:0px; padding:0px;">


                                            <tbody>
                                            <tr>
                                                <td id="invoice_number_label"
                                                    style="padding-left:0px;padding:0px; margin:0px; text-align:right;font-weight:bold;font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    Invoice&nbsp;#

                                                </td>

                                                <td id="invoice_number_value"
                                                    style="width:115px; padding-left:5px; padding-right:5px;text-align:right;font-weight:normal; font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    {{ $invoice->number }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height:20px;"></td>
                                            </tr>


                                            <tr>

                                                <td id="invoice_date_label"
                                                    style="padding-left:0px;text-align:right;font-weight:bold;font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    Invoice&nbsp;Date
                                                </td>

                                                <td id="invoice_date"
                                                    style="padding-left:5px;  padding-right:5px;text-align:right;font-weight:normal; font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    {{ Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td style="height:20px;"></td>
                                            </tr>

                                            <tr>

                                                <td id="payment_due_label"
                                                    style="padding-left:0px;text-align:right;font-weight:bold;font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    Due&nbsp;Date
                                                </td>

                                                <td id="payment_due_date"
                                                    style="padding-left:5px; padding-right:5px;text-align:right;font-weight:normal; font-family:verdana;font-size:13px;color:#3c3c3c;">

                                                    {{ Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}

                                                </td>
                                            </tr>


                                            </tbody>
                                        </table>


                                    </td>
                                </tr>

                                <!-- TB 5 -->

                                </tbody>
                            </table>


                        </td>

                    </tr>

                    <tr>
                        <td><br><br></td>
                    </tr>


                    <!-- TB 3 -->


                    </tbody>
                </table>


                <!-- TB 4 -->

                <table id="dataTable" border="0" cellpadding="0" cellspacing="0"
                       style="width:720px; vertical-align:top;padding-left:0px;">

                    <tbody>

                    <tr id="topRow" class="invoiceDataHeadlineTR"
                        style="background-color:#54A0E7;-webkit-print-color-adjust: exact;">

                        <th id="invoiceItemHeadline" class="invoiceDataHeadlineItem"
                            style="border-color:#ccc;color:white;">Item
                        </th>

                        <th id="invoiceDescriptionHeadline" class="invoiceDataHeadlineDescription"
                            style="border-color:#ccc;color:white;">Description
                        </th>

                        <th class="invoiceDataHeadlineEmpty" style="border-color:#ccc">&nbsp;</th>
                        <th id="invoiceUnitpriceHeadline" class="invoiceDataHeadlineUnitPrice"
                            style="border-color:#ccc;color:white">Unit&nbsp;Price
                        </th>

                        <th id="invoiceQuantityHeadline" class="invoiceDataHeadlineQuantity"
                            style="border-color:#ccc;color:white;">Quantity
                        </th>


                        <th id="invoiceAmountHeadline" class="invoiceDataHeadlineAmount"
                            style="border-color:#ccc;color:white;">Amount
                        </th>

                    </tr>



                    @foreach ($invoice->items as $item)

                        <tr class="invoice-line-row" style="height:30px;line-height:17px;">


                            <td id="item1"
                                style="width:65px; padding:5px; padding-top:5px; padding-left:8px; border-left:1px solid #ccc; font-family:verdana;font-size:12px;color:#3c3c3c;vertical-align:top;">


                                {{ $item->item }}                        </td>


                            <td id="description1" colspan="2"
                                style="width:372px; padding:5px; padding-top:5px; font-family:verdana;font-size:12px;color:#3c3c3c;vertical-align:top;">


                                {{ $item->description }}

                            </td>


                            <td id="unitprice1"
                                style="width:83px; padding:5px; padding-top:5px;vertical-align:top;text-align:right;font-weight:normal; font-family:verdana;font-size:12px;color:#3c3c3c;">

                                {{ $item->unitprice }}
                            </td>


                            <td id="qty1"
                                style="width:83px; padding:5px; padding-top:5px;vertical-align:top;text-align:right;font-weight:normal; font-family:verdana;font-size:12px;color:#3c3c3c;">

                                {{ $item->quantity }}
                            </td>


                            <td id="total1"
                                style="width:83px; padding:5px; padding-top:5px; padding-right:8px; vertical-align:top;text-align:right;border-right:1px solid #ccc;font-weight:normal; font-family:verdana;font-size:12px;color:#3c3c3c;">

                                {{ $item->unitprice * $item->quantity }}

                            </td>

                        </tr>
                    @endforeach


                    <tr>


                        <td id="invoice_notes" colspan="6"
                            style="width:400px;border-bottom:1px solid #ccc; border-left:1px solid #ccc;border-right:1px solid #ccc;vertical-align:top; padding:5px; padding-top:100px; padding-bottom:15px; padding-left:8px; font-family:verdana; font-size:13px; color:#3c3c3c;">

                        </td>


                    </tr>


                    <tr>


                        <td rowspan="7" style="width:0px; border-bottom:1px solid #ccc;border-left:1px solid #ccc;">

                        </td>

                        <td rowspan="7"
                            style="width:420px; padding: 5px; padding-left: -40px; border-bottom:1px solid #ccc;border-right:1px solid #ccc;">

                            {{ $invoice->company->invoice_notes }}
                        </td>


                        <td id="subtotal_headline" colspan="3"
                            style="font-family:verdana; font-size:13px; color:#3c3c3c; font-weight:bold;padding-top:7px;padding-bottom:5px;padding-left:35px;">

                            Subtotal
                        </td>

                        <td id="sum_subtotal" colspan="1"
                            style="width:83px; font-family:verdana;font-size:13px;color:#3c3c3c; text-align:right;border-right:1px solid #ccc;padding-top:7px;padding-bottom:5px;padding-right:8px;">

                            {{ $invoice->subtotal }}
                        </td>

                    </tr>


                    <tr>

                        <td id="sum_total_headline" colspan="3"
                            style="width:145px; border-top:1px solid #ccc;font-family:verdana; font-size:13px; color:#3c3c3c; font-weight:bold;padding-top:7px;padding-bottom:4px;padding-left:35px;">

                            Total
                        </td>

                        <td id="sum_total" colspan="1"
                            style="width:83px; border-top:1px solid #ccc; font-family:verdana;font-size:13px;color:#3c3c3c; text-align:right;border-right:1px solid #ccc;padding-top:7px;padding-bottom:4px;padding-right:8px;">

                            {{ $invoice->total }}
                        </td>

                    </tr>


                    <tr>

                        <td id="amount_paid_headline" colspan="3"
                            style="width:145px; font-family:verdana; -webkit-print-color-adjust: exact; font-size:13px; color:#3c3c3c; font-weight:bold;padding-top:4px;padding-bottom:7px;padding-left:35px;">
                            Amount&nbsp;Paid
                        </td>

                        <td id="amount_paid" colspan="1"
                            style="-webkit-print-color-adjust: exact;width:83px; font-family:verdana;font-size:13px;color:#3c3c3c; text-align:right;border-right:1px solid #ccc;padding-top:4px;padding-bottom:7px;padding-right:8px;">
                            0
                        </td>

                    </tr>


                    <tr>
                        <td id="balance_due_headline" colspan="3"
                            style="background-color:#eaedee; font-family:verdana; -webkit-print-color-adjust: exact; font-size:13px; color:#3c3c3c; font-weight:bold;border-bottom:1px solid #ccc;border-top:1px solid #ccc;padding-top:7px;padding-bottom:7px;padding-left:35px;padding-right:0px;margin-right:0px;">
                            Balance&nbsp;Due&nbsp;<span&nbsp;style="font-family:verdana;&nbsp;font-size:12px;&nbsp;color:#3c3c3c;&nbsp;font-weight:normal;"></span&nbsp;style="font-family:verdana;&nbsp;font-size:12px;&nbsp;color:#3c3c3c;&nbsp;font-weight:normal;">
                        </td>
                        <td id="balance_due"
                            style="width:83px; background-color:#eaedee; -webkit-print-color-adjust: exact; font-family:verdana;font-size:13px;color:#3c3c3c; text-align:right;border-right:1px solid #ccc;border-bottom:1px solid #ccc;border-top:1px solid #ccc;padding-top:7px;padding-bottom:7px;padding-right:8px;">

                            {{ $invoice->balance }}
                        </td>
                    </tr>


                    </tbody>


                    <!-- TB 4 -->

                </table>


            </td>
        </tr>


        <tr style="height:77px;">
            <td>&nbsp;</td>
        </tr>


        <!-- TB 2 -->

        </tbody>
    </table>

</div>
<script>
    window.onload = function () {
        window.print();
    }
</script>