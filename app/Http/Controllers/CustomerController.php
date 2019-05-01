<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->with('invoices')->paginate(15);
        $customers = $this->getFinancicalData($customers);
        //return $customers;

        return view('customers.index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        $customer = new Customer();

        return view('customers.edit', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    public function getFinancicalData($customers)
    {
        foreach ($customers as &$customer) {
            $customer['total'] = $customer->invoices->sum('total');
            $customer['amount_paid'] = $customer->invoices->sum('amount_paid');
            $customer['balance'] = $customer->invoices->sum('balance');
        }
        return $customers;
    }
}
