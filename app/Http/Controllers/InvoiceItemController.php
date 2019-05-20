<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InvoiceItemName;

class InvoiceItemController extends Controller
{
    public function index()
    {
        $items = InvoiceItemName::latest()->get();
        return view('invoices.table-select-item', ['items' => $items]);
    }

    public function store()
    {
        $attributes = \request()->validate([
            'name' => 'required|max:100',
        ]);

        if(\Gate::denies('create', InvoiceItemName::class)) {
            return redirect()
                ->back()
                ->with(['flash' => "Access denied. You cann\'t create items."]);
        }

        InvoiceItemName::create($attributes);

        return redirect()->back()->with(['success' => 'Item has been created']);
    }

    public function update($id)
    {
        $attributes = \request()->validate([
            'name' => 'required|max:100',
        ]);

        $item = InvoiceItemName::find($id);

        if(\Gate::denies('update', $item)) {
            return redirect()
                ->back()
                ->with(['flash' => "Access denied. You cann\'t update items."]);
        }

        $item->update($attributes);

        return redirect()->back()->with(['success' => 'Item has been updated']);
    }

    public function destroy($id)
    {
        $item = InvoiceItemName::find($id);

        if(\Gate::denies('delete', $item)){
            return redirect()
                ->back()
                ->with(['flash' => "Access denied. You cann\'t delete items."]);
        }

        $item->destroy($id);

        return redirect()->back()->with(['success' => 'Item has been deleted']);
    }

    public function selectItems()
    {
        $items = InvoiceItemName::latest()->get();

        return $items;
    }
}
