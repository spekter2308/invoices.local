<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceAdditionalNotes extends Model
{
    protected $guarded = [];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
