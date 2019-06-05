<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceSettings extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
