<?php

namespace App\Filters;

use Illuminate\Http\Request;

/**
 * Class InvoiceFilters
 * @package App
 */
class InvoiceFilters extends Filters
{
    protected $filters = ['byuser', 'status'];
    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return mixed
     */
    protected function byuser($customerId)
    {
        return $this->builder->where('customer_id', $customerId);
    }
    /**
     * Filter the query according to most popular threads.
     *
     * @return $this
     */
    protected function status($status)
    {
        if ($status == 'Late') {
            return $this->builder->where('status', '!=', 'Paid');
        }

        return $this->builder->where('status', $status);
    }
}