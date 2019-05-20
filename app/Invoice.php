<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helper\HasManyRelation;

class Invoice extends Model
{
    use HasManyRelation;

    protected $guarded = [];

    protected $with = ['customer', 'company'];

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

    public function histories()
    {
        return $this->hasMany(InvoiceHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentInvoice::class);
    }

    public function sentMails()
    {
        return $this->hasMany(InvoiceMail::class);
    }

    public function createItems($items)
    {
        foreach ($items as $item) {
            $this->items()->create($item);
        }

        return $this;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
