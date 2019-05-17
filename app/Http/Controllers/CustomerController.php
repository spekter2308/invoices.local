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

        if(\Gate::denies('create', $customer)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t create company.']);
        }

        return view('customers.edit', compact('customer'));
    }

    public function store(Customer $customer)
    {
        $customer->create($this->validateRequest());

        return redirect('/customers/create')->with(['success' => 'Customer has been created']);
    }

    public function update($id)
    {
        $customer = Customer::findOrFail($id);

        if(\Gate::denies('update', $customer)){
            return redirect()
                ->back()
                ->with(['flash' => 'Access denied. You cann\'t update company.']);
        }

        $customer->update($this->validateRequest());

        return redirect('/customers/' . $customer->id . '/edit')->with(['success' => 'Customer\'s data successfully 
        updated']);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect('customers');
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

    protected function validateRequest()
    {
        return \request()->validate([
            'name' => 'required|min:3|max:50',
            'address' => 'nullable|min:3|max:100',
            'email' => 'nullable|email|max:50',
            'phone' => 'nullable|numeric',
            'first_name' => 'nullable|min:3|max:30',
            'last_name' => 'nullable|min:3|max:30',
            'additional_phone' => 'nullable|numeric'
        ]);
    }
}
