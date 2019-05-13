@foreach ($invoices as $invoice)
    @php
        /** @var \App\Invoice $invoice */
    @endphp
    <tr>
        <td>
            <div class="form-check-invoice">
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
                    <ul>
                        <li><a class="dropdown-item" href="#">Edit</a></li>
                        <li><a class="dropdown-item" href="#">Duplicate</a></li>
                        <li><a class="dropdown-item" href="#">Send</a></li>
                        <li><a class="dropdown-item" href="#">RecordPayment</a></li>
                        <li><a class="dropdown-item" href="#">Download/Print PDF</a></li>
                        <li class><a class="dropdown-item" href="#">Change Status to:</a>
                            <a class="dropdown-item" href="#">Paid</a>
                            <a class="dropdown-item" href="#">Partial</a>
                            <a class="dropdown-item" href="#">Sent</a>
                        </li>
                        <li><a class="dropdown-item" href="#">Archive</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
                <a href="/invoices/{{ $invoice->id }}">{{ $invoice->number }}</a>
            </div>
        </td>
        
        <td>
            <a href="/invoices?byuser={{ $invoice->customer->id }}">{{ $invoice->customer->name }}</a>
        </td>
        <td>
            <a href="/invoices?bycompany={{ $invoice->company->id }}">
            @if ($invoice->company->short_name)
                {{ $invoice->company->short_name }}
            @else
                {{ $invoice->company->name }}
            @endif
            </a>
        </td>
        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
        <td
            @if(\Carbon\Carbon::parse($invoice->due_date)->greaterThanOrEqualTo(\Carbon\Carbon::now()))
            style="color: green"
            @else
            style="color: red"
            @endif>
            {{ \Carbon\Carbon::parse($invoice->due_date)->diffInDays(\Carbon\Carbon::now()) }}
        </td>
        <td>{{ $invoice->total }}</td>
        <td>{{ $invoice->balance }}</td>
        <td
            @if ($invoice->status == 'Paid')
                style="color: green;"
            @endif>
            {{ $invoice->status }}
        </td>
        <td>
            <a href="{{route('mark-as-paid', ['id' => $invoice->id])}}" class="btn btn-sm btn-success"
                    @if ($invoice->status == "Paid")
                        style="display: none;"
                    @endif>
                Mark Paid
            </a>
        </td>
    </tr>
@endforeach