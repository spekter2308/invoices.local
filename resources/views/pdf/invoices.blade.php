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
            font-family: Roboto, 'Segoe UI', Tahoma, sans-serif;
            color: #111;
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
            <th align="left" style="width: 30%;">Description</th>
            <th align="center" nowrap>Unit Price</th>
            <th align="center">Quantity</th>
            @if ($invoice->settings->show_tax)
                <th align="center">Tax, %</th>
            @endif
            <th align="right">Amount</th>
        </tr>
    </table>
    <table style="padding: 10px 6px; width: 100%; border: 1px solid black; border-top: none; font-size: 12px;">
        @foreach ($invoice->items as $item)
            <tr>
                <td align="left" style="width: 10%;"> {{ $item->item }}</td>
                <td align="left" style="width: 30%;">{{ $item->description }}</td>
                <td align="center" nowrap>{{ $item->unitprice }}</td>
                <td align="center">{{ $item->quantity }}</td>
                @if ($invoice->settings->show_tax)
                    <td align="center">{{ $item->itemtax }}</td>
                    <td align="right">{{ $item->unitprice * $item->quantity + $item->unitprice * $item->quantity * $item->itemtax/100}}</td>
                @else
                    <td align="right">{{ $item->unitprice * $item->quantity }}</td>
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
                <td colspan="{{ $invoice->settings->show_tax ? '6' : '5' }}" style="padding: 40px 5px 15px;">
                    <span style="text-decoration: underline">NOTES</span>: {!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->invoice_notes))  !!}
                </td>
            </tr>
    </table>
    <div class="page-break"></div>

    <div align="left" style="float: left; width: 39%; height: 0;">

    </div>
    @if ($invoice->settings->show_tax)
        <table class="test" align="right" style="width: 60%; float: right; border: 1px solid black; border-top: none;">
            <tr>
                <td align="left" style="float: left; width: 20%; padding-left: 10px; font-weight: bold">
                    Subtotal
                </td>
                <td align="right" style="float: right; width: 40%; padding-right: 10px">
                    {{ $invoice->subtotal . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid black">
                <td align="left" style="float: left; width: 30%; padding-left: 40px; font-weight: bold">
                    + Tax
                </td>
                <td align="right" style="float: right; width: 30%; padding-right: 10px; margin-left: 30px; font-size: 12px; font-style: italic">
                    {{ $tax . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid black">
                <td align="left" style="float: left; width: 20%; padding-left: 10px; font-weight: bold">
                    Total
                </td>
                <td align="right" style="float: right; width: 40%; padding-right: 10px">
                    {{ $invoice->total . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid black">
                <td align="left" style="float: left; width: 30%; padding-left: 10px; font-weight: bold; white-space: nowrap">
                    Amount Paid
                </td>
                <td align="right" style="float: right; width: 30%; padding-right: 10px">
                    {{ $invoice->amount_paid . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid black">
                <td align="left" style="float: left; width: 30%; padding-left: 10px; font-weight: bold; white-space: nowrap">
                    Balance Due
                </td>
                <td align="right" style="float: right; width: 30%; padding-right: 10px">
                    {{ $invoice->balance . ' ' . $invoice->settings->currency}}
                </td>
            </tr>
        </table>
    @else
        <table align="right" style="width: 60%; float: right; border: 1px solid black; border-top: none;">
            <tr style="border-bottom: 1px solid black">
                <td align="left" style="float: left; width: 20%; padding-left: 10px; font-weight: bold">
                    Subtotal
                </td>
                <td align="right" style="float: right; width: 40%; padding-right: 10px">
                    {{ $invoice->subtotal . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr style="border-bottom: none;">
                <td align="left" style="float: left; width: 20%; padding-left: 10px; font-weight: bold">
                    Total
                </td>
                <td align="right" style="float: right; width: 40%; padding-right: 10px">
                    {{ $invoice->subtotal . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr>
                <td align="left" style="float: left; width: 30%; padding-left: 10px; font-weight: bold; white-space: nowrap;">
                    Amount Paid
                </td>
                <td align="right" style="float: right; width: 30%; padding-right: 10px">
                    {{ $invoice->amount_paid . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
            <tr>
                <td align="left" style="float: left; width: 30%; padding-left: 10px; font-weight: bold; white-space: nowrap;">
                    Balance Due
                </td>
                <td align="right" style="float: right; width: 30%; padding-right: 10px">
                    {{ $balance . ' ' . $invoice->settings->currency }}
                </td>
            </tr>
        </table>
    @endif
       
    

</div>

</body>
<script>
    window.onload = function () {
        window.print();
    }
</script>
</html>
