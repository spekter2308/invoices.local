<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    protected $appends = [
         'text'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }
}
