<?php
/**
 * Created by PhpStorm.
 * User: Specter
 * Date: 20.09.2019
 * Time: 22:59
 */

namespace App\Services;

use App\Customer;
use App\Exports\InvoiceStatementExport;
use Carbon\Carbon;
use Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class StatementService implements StatementServiceInterface
{
    protected $customer;

    /**
     * StatementService constructor.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function generateStatementExcel($id)
    {
        $customer = $this->getCustomerData($id);
        $customerInvoices = $customer->invoices;
        if ($customerInvoices->count()) {
            $reader = new Xls();
            $spreadsheet = $reader->load('storage/Statement_template.xls');
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('C6', $customer->name);
            $sheet->setCellValue('C7', $customer->address);
            $sheet->setCellValue('C12', "Date: " . Carbon::now()->format('d F Y'));

            $count = 15;
            foreach ($customerInvoices as $invoice) {
                $sheet->setCellValue('C' . $count, Carbon::parse($invoice->invoice_date)->format('d.m.Y'));
                $sheet->setCellValue('D' . $count, $invoice->number);
                $sheet->setCellValue('E' . $count, 'invoice');
                $sheet->mergeCells('E' . $count . ':G' . $count);
                $sheet->setCellValue('H' . $count, $invoice->settings->show_tax ? $invoice->total : $invoice->subtotal);
                $sheet->mergeCells('H' . $count . ':I' . $count);
                $sheet->setCellValue('J' . $count, $invoice->amount_paid);
                $sheet->setCellValue('K' . $count, $invoice->balance);
                $sheet->mergeCells('K' . $count . ':L' . $count);
                $count++;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xls");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="file.xls"');
            $writer->save("php://output");
        }

        return redirect()->back()->with(['flash' => 'Current customer does not have invoices', 'tabName' => 'active']);
    }

    public function generateStatementPdf($id)
    {
        $customer = $this->getCustomerData($id);
        $customerInvoices = $customer->invoices;
        if ($customerInvoices->count()) {
            $reader = new Xls();
            $spreadsheet = $reader->load('storage/Statement_template.xls');
            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('C6', $customer->name);
            $sheet->setCellValue('C7', $customer->address);
            $sheet->setCellValue('C12', "Date: " . Carbon::now()->format('d F Y'));

            $count = 15;
            foreach ($customerInvoices as $invoice) {
                $sheet->setCellValue('C' . $count, Carbon::parse($invoice->invoice_date)->format('d.m.Y'));
                $sheet->setCellValue('D' . $count, $invoice->number);
                $sheet->setCellValue('E' . $count, 'invoice');
                $sheet->mergeCells('E' . $count . ':G' . $count);
                $sheet->setCellValue('H' . $count, $invoice->settings->show_tax ? $invoice->total : $invoice->subtotal);
                $sheet->mergeCells('H' . $count . ':I' . $count);
                $sheet->setCellValue('J' . $count, $invoice->amount_paid);
                $sheet->setCellValue('K' . $count, $invoice->balance);
                $sheet->mergeCells('K' . $count . ':L' . $count);
                $count++;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
            $writer->save('test.pdf');
        }

        return redirect()->back()->with(['flash' => 'Current customer does not have invoices', 'tabName' => 'active']);
    }

    protected function getCustomerData($id) {
        return $this->customer->find($id);
    }
}