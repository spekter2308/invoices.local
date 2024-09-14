<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>

    <style>
        .page-break {
            page-break-inside: avoid;
        }
        body {
            /*font-family: Roboto, 'Segoe UI', Tahoma, sans-serif;
            color: #111;*/
        }
        .logo {
            background-image: url('/upload/company/{{$invoice->company->logo_img}}');
            float: right;
            height: 80px; width: 300px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .paid {
            display: block;
            position: relative;
            background-image: url("public/img/paid.jpg");
            height: 60px; width: 100px;
            margin: -30px 20% 0;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

    </style>
</head>
<body>

<div class="invoice-pdf">

    <div style="height: 150px;">
        <div align="left" style="float: left; width: 45%;">
            {{ $invoice->company->name }} <br>
            {!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->address)) !!}
        </div>
        <div align="right" style="float: right; width: 54%;">
            @if ($invoice->company->logo_img)
                <div class="logo"></div>
                <div style="clear: both; margin: 0pt; padding: 0pt;"></div>
                <h3 align="right" style="margin: 50px 0 35px; letter-spacing: 6px">INVOICE</h3>
            @else
                <h1 align="right" style="margin: 50px 0 35px; letter-spacing: 6px">INVOICE</h1>
            @endif
        </div>
        <div style="clear: both; margin: 0pt; padding: 0pt;"></div>
    </div>

    @if ($invoice->status == 'Paid')
        <div class="paid"></div>
    @endif

    <div style="height: 140px;">
        <div align="left" style="float: left; width: 45%;">
            <br> {{ $invoice->customer->name }} <br>
            {!! nl2br(str_replace(" ", " &nbsp;", $invoice->customer->address))  !!}
        </div>
        <div align="right" style="float: right; width: 54%;">
            <table align="right" style="border-spacing:0 18px;">
                <tr>
                    <td align="right" style="font-weight: bold">Invoice #</td>
                    <td align="right" style="padding-left: 40px">{{ $invoice->number }}</td>
                </tr>
                <br>
                <tr>
                    <td align="right" style="font-weight: bold">Invoice Date</td>
                    <td align="right" style="padding-left: 40px">{{ $invoice->pdf_invoice_date }}</td>
                </tr>
                <br>
                <tr>
                    <td align="right" style="font-weight: bold">Due Date</td>
                    <td align="right" style="padding-left: 40px">{{ $invoice->pdf_due_date }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both; margin: 0pt; padding: 0pt;"></div>
    </div>

    <table style="margin-top: 20px; padding: 6px 6px; width: 100%; border: 1px solid black; background-color: #e6e5e5; font-size: 12px;">
        <tr>
            <th align="left" style="width: 10%;">Item</th>
            <th align="left" style="width: 40%;">Description</th>
            <th align="center" style="width: 10%;" nowrap>Unit Price</th>
            <th align="center" style="width: 10%;">Quantity</th>
            @if ($invoice->settings->show_tax)
                <th align="center" style="width: 10%;" >Tax, %</th>
                <th align="right" style="width: 10%;">Amount</th>
            @else
                <th align="right" style="width: 20%;">Amount</th>
            @endif

        </tr>
    </table>
    <table style="padding: 10px 6px; width: 100%; border: 1px solid black; border-top: none; font-size: 12px;">
        @foreach ($invoice->items as $item)
            <tr>
                <td align="left" style="width: 10%;"> {{ $item->item }}</td>
                <td align="left" style="width: 40%;">{{ $item->description }}</td>
                <td align="center" style="width: 10%;" nowrap>
                    @if(floor($item->unitprice) == $item->unitprice)
                        {{ number_format($item->unitprice, 0) }}  {{-- Outputs 27 if 27.0000000000 --}}
                    @else
                        {{ rtrim(rtrim(number_format($item->unitprice, 10), '0'), '.') }} {{-- Outputs 27.0004 if 27.000400000 --}}
                    @endif
                </td>
                <td align="center" style="width: 10%;">
                    @if(floor($item->quantity) == $item->quantity)
                        {{ number_format($item->quantity, 0) }}  {{-- Outputs 27 if 27.0000000000 --}}
                    @else
                        {{ rtrim(rtrim(number_format($item->quantity, 10), '0'), '.') }} {{-- Outputs 27.0004 if 27.000400000 --}}
                    @endif
                </td>
                @if ($invoice->settings->show_tax)
                    <td align="center" style="width: 10%;">{{ $item->itemtax }}</td>
                    <td align="right" style="width: 10%;">{{ round($item->unitprice * $item->quantity + $item->unitprice * $item->quantity * $item->itemtax/100, 2)}}</td>
                @else
                    <td align="right" style="width: 20%;">{{ round($item->unitprice * $item->quantity, 2) }}</td>
                @endif
            </tr>
        @endforeach
        @if ($invoice->settings->show_payment)
            <tr>
                <td align="left" style="padding: 5px 5px;">Payment:</td>
                <td align="right" colspan="{{ $invoice->settings->show_tax ? '5' : '4' }}" style="padding: 5px 5px;">{{ $invoice->amount_paid }}</td>
            </tr>
        @endif
            <tr>
                <td colspan="{{ $invoice->settings->show_tax ? '6' : '5' }}" style="padding: 40px 5px 15px; display: flex; flex-direction: column; gap: 10px;">
                    <span style="text-decoration: underline">NOTES</span>: <br><span>{!! nl2br(str_replace(" ", " &nbsp;", $invoice->invoice_notes))  !!}</span>
                </td>
            </tr>
    </table>
    <table style="padding: -3px; width: 100%; border: 1px solid black; border-top: none; overflow: hidden; font-size: 13px">
        <tr>
            <td align="left" style="width: 55%;"></td>
            <td align="right" style="border-left: 1px solid #000; padding: 0;">
                @if ($invoice->settings->show_tax)
                    <table width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td align="left" style="padding-top: 10px; padding-left: 20px; font-weight: bold;">
                                Subtotal
                            </td>
                            <td align="right" style="padding-top: 10px; padding-right: 10px;">
                                {{ $invoice->settings->currency . ' ' . $invoice->subtotal }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="padding-bottom: 10px; padding-left: 50px; font-weight: bold; border-bottom: 1px solid #000; font-weight: normal; font-style: italic">
                                + Tax
                            </td>
                            <td align="right" style=" padding-bottom: 10px; padding-right: 10px; margin-left: 30px; font-size: 12px; font-style: italic; border-bottom: 1px solid #000;">
                                {{ $invoice->settings->currency . ' ' . $tax }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="padding: 10px 0 5px 20px; font-weight: bold;">
                                Total
                            </td>
                            <td align="right" style="padding: 10px 10px 5px 0px;">
                                {{ $invoice->settings->currency . ' ' . $invoice->total }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="padding: 5px 0 10px 20px; font-weight: bold; white-space: nowrap; border-bottom: 1px solid #000;">
                                Amount Paid
                            </td>
                            <td align="right" style="padding: 5px 10px 10px 0px; border-bottom: 1px solid #000;">
                                {{ $invoice->settings->currency . ' ' . $invoice->amount_paid }}
                            </td>
                        </tr>
                        @if ($invoice->overpayment != 0)
                            <tr>
                                <td align="left" style="padding: 10px; padding-left: 20px; font-weight: bold; white-space: nowrap;">
                                    @if ($invoice->overpayment > 0)
                                        Underpayment
                                    @else
                                        Overpayment
                                    @endif
                                </td>
                                <td align="right" style="padding: 10px; padding-right: 10px">
                                    {{ $invoice->settings->currency . ' ' . $invoice->overpayment }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td align="left" style="padding: 10px; padding-left: 20px; font-weight: bold; white-space: nowrap;">
                                Balance Due
                            </td>
                            <td align="right" style="padding: 10px; padding-right: 10px">
                                {{ $invoice->settings->currency . ' ' . $invoice->balance }}
                            </td>
                        </tr>
                    </table>
                @else
                    <table width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td align="left" style="padding: 10px; padding-left: 20px; font-weight: bold; border-bottom: 1px solid #000;">
                                Subtotal
                            </td>
                            <td align="right" style="padding: 10px; padding-right: 10px; border-bottom: 1px solid #000;">
                                {{ $invoice->settings->currency . ' ' . $invoice->subtotal }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="padding: 10px 0 5px 20px; font-weight: bold;">
                                Total
                            </td>
                            <td align="right" style="padding: 10px 10px 5px 0px;">
                                {{ $invoice->settings->currency . ' ' . $invoice->subtotal }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" style="padding: 5px 0 10px 20px; font-weight: bold; white-space: nowrap;  border-bottom: 1px solid #000;">
                                Amount Paid
                            </td>
                            <td align="right" style="padding: 5px 10px 10px 0px; border-bottom: 1px solid #000;">
                                {{ $invoice->settings->currency . ' ' . $invoice->amount_paid }}
                            </td>
                        </tr>
                        @if ($invoice->overpayment != 0)
                            <tr>
                                <td align="left" style="padding: 10px; padding-left: 20px; font-weight: bold; white-space: nowrap;">
                                    @if ($invoice->overpayment > 0)
                                        Underpayment
                                    @else
                                        Overpayment
                                    @endif
                                </td>
                                <td align="right" style="padding: 10px; padding-right: 10px">
                                    {{ $invoice->settings->currency . ' ' . $invoice->overpayment }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td align="left" style="padding: 10px; padding-left: 20px; font-weight: bold; white-space: nowrap;">
                                Balance Due
                            </td>
                            <td align="right" style="padding: 10px; padding-right: 10px">
                                {{ $invoice->settings->currency . ' ' . $balance }}
                            </td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>
    </table>

</div>

</body>
<script>
    window.onload = function () {
        window.print();
    }
</script>
</html>
