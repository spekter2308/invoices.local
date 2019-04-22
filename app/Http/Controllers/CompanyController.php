<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{

    public function search()
    {
        $results = Company::orderBy('short_name')
            ->when(\request('q'), function($query) {
                $query->where('short_name' , 'like', '%'.\request('q').'%')
                    ->orWhere('name', 'like', '%'.\request('q').'%');
            })
            ->limit(6)
            ->get();

        return response()
            ->json(['results' => $results]);
    }
}
