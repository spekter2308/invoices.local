<?php
namespace App\Filters;

use Illuminate\Http\Request;
/**
 * Class Filters
 */
abstract class Filters
{
    protected $request, $builder;
    protected $filters = [];
    /**
     * InvoiceFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }
    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}