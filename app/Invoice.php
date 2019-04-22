<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helper\HasManyRelation;

class Invoice extends Model
{
    use HasManyRelation;

    protected $guarded = [];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
