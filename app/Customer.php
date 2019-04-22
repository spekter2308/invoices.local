<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'email',
        'first_name', 'last_name', 'additional_phone'
    ];

    protected $appends = [
        'text'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }
}
