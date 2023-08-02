<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Counter;
use App\Invoice;

class CounterController extends Controller
{
    public function index()
    {
        $counter = Counter::where(['user_id' => auth()->id()])->first();
        $invoiceNumbers = Invoice::all()->sortBy('number')->pluck('number')->toArray();
        $prefix = $counter->prefix;
        $start = $counter->start;
        $increment = $counter->increment;
        $postfix = $counter->postfix;

        $invoiceNumber = $this->checkInArray($prefix, $start, $increment, $postfix, $invoiceNumbers, $increment);

        return \response()->json(['invoiceNumber' => $invoiceNumber, 'invoiceNumbers' => $invoiceNumbers]);
    }

    public function update($id)
    {
        $counter = Counter::findOrFail($id);
        $counter->update($this->counterValidation());

        return \response()->json(['message' => 'invoice number format updated success']);
    }

    public function counterValidation() {
        return \request()->validate([
                'prefix' => 'nullable',
                'start' => 'integer|nullable|min:0',
                'postfix' => 'nullable',
                'increment' => 'required|integer|min:1'
            ]);
    }

    public function checkInArray($prefix, $start, $increment, $postfix, $array, $old_increment)
    {
        $result = $prefix . strval($start + $increment) . $postfix;
        if (in_array($result, $array)) {
            return $this->checkInArray($prefix, $start, $increment + $old_increment, $postfix, $array, $old_increment);
        }
        return strval($result);
    }
}
