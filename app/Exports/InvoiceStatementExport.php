<?php
/**
 * Created by PhpStorm.
 * User: Specter
 * Date: 22.09.2019
 * Time: 21:38
 */

namespace App\Exports;


use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceStatementExport implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomStartCell, WithCustomCsvSettings
{
    public $customerInvoices;

    public function __construct($id)
    {
        $this->customerInvoices = Customer::find($id)->invoices;
    }

    public function collection()
    {
        return $this->customerInvoices;
    }

    public function headings(): array
    {
        return ['Statement', 'Global Soft Group Corp'];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function startCell(): string
    {
        return 'C2';
    }

    public function getCsvSettings(): array
    {
        // TODO: Implement getCsvSettings() method.
    }
}