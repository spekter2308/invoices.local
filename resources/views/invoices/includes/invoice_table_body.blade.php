@foreach ($invoices as $invoice)
    @php
        /** @var \App\Invoice $invoice */
    @endphp
    <tr>
        <td>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check-{{ $invoice->id }}">
                <label for="check-{{ $invoice->id }}"></label>
            </div>
        </td>
        
        <td scope="row">
            <div class="dropdown">
                <button class="btn-select dropdown-toggle bg-white" type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                </div>
                {{ $invoice->number }}
            </div>
        </td>
        
        <td><a href="{{route('customer-invoices', ['id' => $invoice->customer->id])}}">{{ $invoice->customer->name
        }}</a></td>
        <td><a href="#">{{ $invoice->company->name }}</a></td>
        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
        <td
                @if(\Carbon\Carbon::parse($invoice->due_date)->greaterThanOrEqualTo(\Carbon\Carbon::now()))
                style="color: green;"
                @else
                style="color: red";
                @endif
        >{{
                                     \Carbon\Carbon::parse($invoice->due_date)->diffInDays(\Carbon\Carbon::now())
                                     }}
        </td>
        <td>{{ $invoice->total }}</td>
        <td>{{ $invoice->balance }}</td>
        <td>{{ $invoice->status }}</td>
        <td>
            <button class="btn btn-sm btn-success"
                    @if ($invoice->status == "Paid")
                    disabled
                    @endif>Mark Paid</button>
        </td>
    </tr>
@endforeach