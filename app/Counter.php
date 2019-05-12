<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'user_id', 'prefix', 'start', 'postfix', 'increment'
    ];
}
