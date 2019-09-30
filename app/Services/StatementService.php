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
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
            $spreadsheet = $this->getCustomerStatementInfo($customer);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xls");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="statement.xls"');
            $writer->save("php://output");
        } else {
            return redirect()->back()->with(['flash' => 'Current customer does not have invoices', 'tabName' => 'active']);
        }
    }

    public function generateStatementPdf($id)
    {
        $customer = $this->getCustomerData($id);
        $customerInvoices = $customer->invoices;
        if ($customerInvoices->count()) {
            $spreadsheet = $this->getCustomerStatementInfo($customer);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
            header('Content-Disposition: attachment; filename="statement.pdf"');
            $writer->save("php://output");
        } else {
            return redirect()->back()->with(['flash' => 'Current customer does not have invoices', 'tabName' => 'active']);
        }
    }

    protected function getCustomerStatementInfo($customer) {

        $boldFont = [
            'font' => [
                'name' => 'Arial',
                'bold' => TRUE,
                'italic' => FALSE,
                'strikethrough' => FALSE,
                'color' => [
                    'rgb' => '000000'
                ]
            ],
            'alignment' => [
                  'horizontal' => Alignment::HORIZONTAL_CENTER,
                  'vertical' => Alignment::VERTICAL_CENTER,
                  'wrapText' => true,
              ],

       ];

        $reader = new Xls();
        $spreadsheet = $reader->load('storage/Statement_template.xls');
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C6', $customer->name);
        $sheet->setCellValue('C7', $customer->address);
        $sheet->setCellValue('C12', "Date: " . Carbon::now()->format('d F Y'));

        $count = 15;
        $invoices = $customer->invoices;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('C' . $count, Carbon::parse($invoice->invoice_date)->format('d.m.Y'));
            $sheet->setCellValue('D' . $count, $invoice->number);
            $sheet->setCellValue('E' . $count, 'invoice');
            $sheet->mergeCells('E' . $count . ':G' . $count);
            $sheet->setCellValue('H' . $count, $invoice->settings->currency . ($invoice->settings->show_tax ? $invoice->total : $invoice->subtotal));
            $sheet->mergeCells('H' . $count . ':I' . $count);
            $sheet->setCellValue('J' . $count, $invoice->settings->currency . $invoice->amount_paid);
            $sheet->setCellValue('K' . $count, $invoice->settings->currency . $invoice->balance);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $count++;
        }

        $sheet->setCellValue('C' . ($count + 2), 'Thank you for your business!');
        $sheet->mergeCells('C' . ($count + 2) . ':F' . ($count + 2));
        $sheet->getStyle('C' . ($count + 2))->applyFromArray($boldFont);

        $invoices_usd = $invoices->where('settings.currency', '=', '$');
        $invoices_euro = $invoices->where('settings.currency', '=', '€');
        $invoices_pound = $invoices->where('settings.currency', '=', '£');

        $allRemainingUsd = $this->getAllRemaining($invoices_usd);
        $allRemainingEuro= $this->getAllRemaining($invoices_euro);
        $allRemainingPound= $this->getAllRemaining($invoices_pound);

        if ($invoices_usd->count()) {
            $sheet->setCellValue('J' . ($count + 1), 'Subtotal');
            $sheet->setCellValue('K' . ($count + 1), '$' . $allRemainingUsd);
            $sheet->mergeCells('K' . ($count + 1) . ':L' . ($count + 1));
            $count+=2;
        } else {
            $count+=2;
        }
        if ($invoices_euro->count()) {
            $sheet->setCellValue('J' . $count, 'Subtotal');
            $sheet->setCellValue('K' . $count, '€' . $allRemainingEuro);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $count++;
        }
        if ($invoices_pound->count()) {
            $sheet->setCellValue('J' . $count, 'Subtotal');
            $sheet->setCellValue('K' . $count, '£' . $allRemainingPound);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $count+=2;
        } else {
            $count+=2;
        }

        //Total parts
        if ($invoices_usd->count()) {
            $sheet->setCellValue('J' . $count, 'Total Due');
            $sheet->getStyle('J' . $count)->applyFromArray($boldFont);
            $sheet->setCellValue('K' . $count, '$' . $allRemainingUsd);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $sheet->getStyle('K' . $count)->applyFromArray($boldFont);
            $count++;
        }
        if ($invoices_euro->count()) {
            $sheet->setCellValue('J' . $count, 'Total Due');
            $sheet->getStyle('J' . $count)->applyFromArray($boldFont);
            $sheet->setCellValue('K' . $count, '€' . $allRemainingEuro);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $sheet->getStyle('K' . $count)->applyFromArray($boldFont);
            $count++;
        }
        if ($invoices_pound->count()) {
            $sheet->setCellValue('J' . $count, 'Total Due');
            $sheet->getStyle('J' . $count)->applyFromArray($boldFont);
            $sheet->setCellValue('K' . $count, '£' . $allRemainingPound);
            $sheet->mergeCells('K' . $count . ':L' . $count);
            $sheet->getStyle('K' . $count)->applyFromArray($boldFont);
        }


        return $spreadsheet;
    }

    /**
     * Get result total for invoices
     *
     * @param $invoices
     */
    protected function getAllRemaining($invoices)
    {
        $balance = 0;
        foreach ($invoices as $invoice) {
            $balance += $invoice->balance;
        }

        return $balance;
    }

    protected function getCustomerData($id) {
        return $this->customer->find($id);
    }
}