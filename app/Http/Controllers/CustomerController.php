<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function search()
    {
        $results = Customer::orderBy('name')
            ->when(\request('q'), function($query) {
                $query->where('name' , 'like', '%'.\request('q').'%')
                      ->orWhere('email', 'like', '%'.\request('q').'%');
        })
            ->limit(6)
            ->get();

        return response()
            ->json(['results' => $results]);
    }
}
