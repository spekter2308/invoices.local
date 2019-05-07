<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItemName extends Model
{
    protected $table = 'invoices_items';
    protected $fillable = ['name'];
}
