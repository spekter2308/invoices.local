<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'description', 'unit_price', 'quantity', 'tax', 'amount'
    ];

    protected $appends = ['text'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }
}
