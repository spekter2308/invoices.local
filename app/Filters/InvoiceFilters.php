<?php

namespace App\Filters;

use App\Customer;
use Illuminate\Http\Request;

/**
 * Class InvoiceFilters
 * @package App
 */
class InvoiceFilters extends Filters
{
    protected $filters = ['byuser', 'status', 'bycompany', 'sortby'];

    /**
     * Sort the query by a given param
     *
     * @param string $customerId
     * @return mixed
     */
    protected function sortby($param)
    {
        $orderBy = ($this->request->order == 'true') ? 'desc' : 'asc';
        if ($param == 'number') {
            return $this->builder->orderByRaw("CAST(number as UNSIGNED) $orderBy");
        }
        if ($param == 'customer') {
            return $this->builder->join('customers', 'customers.id', 'invoices.customer_id')->select('invoices.*', 'customers.name as customer_name')->orderBy('customer_name', $orderBy);
        }
        if ($param == 'company') {
            return $this->builder->join('companies', 'companies.id', 'invoices.company_id')->select('invoices.*', \DB::raw('IF(companies.short_name IS NOT NULL, companies.short_name, companies.name) as company_name'))->orderBy('company_name', $orderBy);
        }
        if ($param == 'invoice_date') {
            return $this->builder->orderBy('invoice_date', $orderBy);
        }
        if ($param == 'diffdays') {
            return $this->builder->select('invoices.*')->addSelect(\DB::raw('ABS(DATEDIFF(due_date, CURDATE())) as days'))->orderBy('days', $orderBy);
        }
        if ($param == 'subtotal') {
            return $this->builder->join('invoice_settings', 'invoice_settings.invoice_id', 'invoices.id')->select('invoices.*', \DB::raw('IF(invoice_settings.show_tax = 0, invoices.subtotal, invoices.total) as total'))->orderBy('total', $orderBy);
        }
        if ($param == 'balance') {
            return $this->builder->orderBy('balance', $orderBy);
        }
    }
    /**
     * Filter the query by a given customer id
     *
     * @param string $customerId
     * @return mixed
     */
    protected function byuser($customerId)
    {
        return $this->builder->where('customer_id', $customerId);
    }
    /**
     * Filter the query by a given company id
     *
     * @param string $companyId
     * @return mixed
     */
    protected function bycompany($companyId)
    {
        return $this->builder->where('company_id', $companyId);
    }
    /**
     * Filter the query by status
     *
     * @return $this
     */
    protected function status($status)
    {
        if (!$status || $status == 'All') {
            return $this->builder->where('status', '!=', 'Archive');
        }

        if ($status == 'Late') {
            return $this->builder->where('due_date', '<', \Carbon\Carbon::now())->where('status', '!=', 'Paid')->where('status', '!=', 'Archive');
        }

        return $this->builder->where('status', $status);
    }
}