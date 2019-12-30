<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvoiceAdditionalNotes;
use App\InvoiceHistory;

class InvoiceAdditionalNotesController extends Controller
{
    public function saveNote(Request $request)
    {
        if ($request->invoice_id) {
            InvoiceAdditionalNotes::create([
                'invoice_id' => $request->invoice_id,
                'notes' => $request->notes
            ]);

            InvoiceHistory::create([
                'invoice_id' => $request->invoice_id,
                'user_id' => auth()->id(),
                'changes' => $request->notes
            ]);
        }
    }
}
