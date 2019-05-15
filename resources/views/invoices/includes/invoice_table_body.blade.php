@forelse ($invoices as $invoice)
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
                    <ul class="action-list list-group list-group-flush">
                        <li class="list-group-item"><i class="far fa-edit"></i><a href="/invoices/{{ $invoice->id }}/edit">Edit</a></li>
                        <li class="list-group-item"><i class="far fa-copy"></i><a href="#">Duplicate</a></li>
                        <li class="list-group-item"><i class="far fa-paper-plane"></i><a href="/invoice-mail/create/{{ $invoice->id }}">Send</a></li>
                        <li class="list-group-item"><i class="far fa-money-bill-alt"></i><a href="#">RecordPayment</a></li>
                        <li class="list-group-item"><i class="far fa-file-pdf"></i><a href="{{route('generate-pdf', ['invoice' => $invoice->id])}}">Download</a> <a target="_blank" href="{{route('generate-pdf', ['invoice' => $invoice->id, 'print' => 'print'])}}">Print PDF</a></li>
                        <li class="list-group-item show-status-list"><i class="fas fa-sync-alt"></i><a href="#">Change Status to:<i class="fas icon-rigth fa-caret-right"></i></a>
                            <ul class="list-status list-group list-group-flush">
                                <li class="list-group-item"><a href="#">Paid</a></li>
                                <li class="list-group-item"><a href="#">Partial</a></li>
                                <li class="list-group-item"><a href="#">Sent</a></li>
                            </ul>
                        </li>
                        <li class="list-group-item"><i class="far fa-file-archive"></i><a href="#">Archive</a></li>
                        <li class="list-group-item"><i class="far fa-trash-alt"></i><a href="#">Delete</a></li>
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
@empty
    <tr>
        <td><h3>No data</h3></td>
    </tr>
@endforelse