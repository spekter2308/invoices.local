<?php

namespace App\Filters;

use Illuminate\Http\Request;

/**
 * Class InvoiceFilters
 * @package App
 */
class InvoiceFilters extends Filters
{
    protected $filters = ['byuser', 'status', 'bycompany'];
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
        if (!$status) {
            return $this->builder->where('status', '!=', 'Archive');
        }

        if ($status == 'Late') {
            return $this->builder->where('due_date', '<', \Carbon\Carbon::now())->where('status', '!=', 'Paid')->where('status', '!=', 'Archive');
        }

        return $this->builder->where('status', $status);
    }
}