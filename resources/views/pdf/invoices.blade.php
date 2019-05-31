<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
</head>
<body>


<style>
    .page-break {
        page-break-inside: avoid;
    }
    body {
        font-family: Roboto, 'Segoe UI', Tahoma, sans-serif;
        color: #111;
    }
    .logo {
        background-image: url("/upload/company/{{$invoice->company->logo_img}}");
        float: right;
        height: 80px; width: 300px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .paid {
        display: block;
        position: relative;
        background-image: url("/img/paid.jpg");
        height: 60px; width: 100px;
        margin: -30px 20% 0;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

</style>
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
                    <td align="right" style="padding-left: 40px">{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                </tr>
                <br>
                <tr>
                    <td align="right" style="font-weight: bold">Due Date</td>
                    <td align="right" style="padding-left: 40px">{{ Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both; margin: 0pt; padding: 0pt;"></div>
    </div>

    <table style="margin-top: 20px; padding: 6px 6px; width: 100%; border: 1px solid black; background-color: #e6e5e5; font-size: 13px;">
        <tr>
            <th align="left">Item</th>
            <th align="left" style="width: 30%;">Description</th>
            <th align="center" nowrap>Unit Price</th>
            <th align="center">Quantity</th>
            @if ($invoice->total != $invoice->subtotal)
                <th align="center">Tax</th>
            @endif
            <th align="right">Amount</th>
        </tr>
    </table>
    <table style="padding: 10px 6px; width: 100%; border: 1px solid black; border-top: none; font-size: 12px;">
        @foreach ($invoice->items as $item)
            <tr>
                <td align="left"> {{ $item->item }}</td>
                <td align="left" style="width: 25%;">{{ $item->description }}</td>
                <td align="center" nowrap>{{ $item->unitprice }}</td>
                <td align="right">{{ $item->quantity }}</td>
                @if ($item->tax != 0)
                    <th align="center">Tax</th>
                @endif
                <td align="right">{{ $item->unitprice * $item->quantity }}</td>
            </tr>
        @endforeach
            <tr>
                <td colspan="{{ $invoice->total == $invoice->subtotal ? '5' : '6' }}" style="padding: 40px 5px 15px;">
                    <span style="text-decoration: underline">NOTES</span>: {!! nl2br(str_replace(" ", " &nbsp;", $invoice->company->invoice_notes))  !!}
                </td>
            </tr>
    </table>
    <div class="page-break"></div>
    <div style="border: 1px solid #000; border-top: none;">
        <div align="left" style="float: left; width: 49%;">
           
        </div>
        <div align="right" style="float: right; width: 50%; border-left: 1px solid #000; margin-right: 5px;">
            <div style="width: 100%; border-bottom: 1px solid #000; padding: 5px 0;">
                <div align="left" style="float: left; width: 20%; padding-left: 40px">
                    <span style="font-weight: bold">Subtotal</span>
                </div>
                <div align="right" style="float: right; width: 40%; padding-right: 40px">
                    {{ $invoice->subtotal }}
                </div>
            </div>
            <div style="width: 100%; padding: 5px 0;">
                <div align="left" style="float: left; width: 20%; padding-left: 40px">
                    <span style="font-weight: bold">Total</span>
                </div>
                <div align="right" style="float: right; width: 40%; padding-right: 40px">
                    {{ $invoice->total }}
                </div>
            </div>
            <div style="width: 100%; border-bottom: 1px solid #000; padding: 5px 0;">
                <div align="left" style="float: left; width: 30%; padding-left: 40px">
                    <span style="font-weight: bold">Amount Paid</span>
                </div>
                <div align="right" style="float: right; width: 40%; padding-right: 40px">
                    {{ $invoice->amount_paid }}
                </div>
            </div>
            <div style="width: 100%; padding: 5px 0;">
                <div align="left" style="float: left; width: 30%; padding-left: 40px">
                    <span style="font-weight: bold">Balance Due</span>
                </div>
                <div align="right" style="float: right; width: 40%; padding-right: 40px">
                    {{ $invoice->balance }}
                </div>
            </div>
            {{--<table align="right" style="border-spacing:0 18px;">
                <tr>
                    <td align="right" style="font-weight: bold">Invoice #</td>
                    <td align="right" style="padding-left: 40px">{{ $invoice->number }}</td>
                </tr>
                <br>
                <tr>
                    <td align="right" style="font-weight: bold">Invoice Date</td>
                    <td align="right" style="padding-left: 40px">{{ Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                </tr>
                <br>
                <tr>
                    <td align="right" style="font-weight: bold">Due Date</td>
                    <td align="right" style="padding-left: 40px">{{ Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                </tr>
            </table>--}}
        </div>
       
    

</div>

</body>
<script>
    window.onload = function () {
        window.print();
    }
</script>
</html>
