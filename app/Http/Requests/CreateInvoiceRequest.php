<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selectedCompany' => 'required|integer',
            'selectedCustomer' => 'required',
            'selectedDateFrom' => 'required|date|date_format:Y-m-d',
            'selectedDateTo' => 'required|date|date_format:Y-m-d',
            'selectedInvoiceNumber' => 'required|string',
            'selectedItems' => 'required|array|min:1',
            'selectedItems.*.item' => 'required|string',
            'selectedItems.*.description' => 'nullable',
            'selectedItems.*.quantity' => 'required|integer|min:1',
            'selectedItems.*.unitprice' => 'required|integer|min:1'
        ];
    }
}
