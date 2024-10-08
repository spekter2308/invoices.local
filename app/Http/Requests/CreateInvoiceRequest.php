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
            'selectedDateFrom' => 'required|date',
            'selectedDateTo' => 'required|date',
            'selectedInvoiceNumber' => 'required|string',
            'selectedItems' => 'required|array|min:1',
            'selectedNotes' => '',
            'selectedItems.*.item' => 'nullable',
            'selectedItems.*.description' => 'nullable',
            'selectedItems.*.quantity' => 'required|numeric|min:0.01',
            'selectedItems.*.unitprice' => 'required|numeric',
            'selectedSettings' => 'required|array'
        ];
    }
}
