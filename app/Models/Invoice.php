<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'customer_id', 'id');
    }

    public function invoice_pay()
    {
        return $this->hasMany(InvoicePay::class);
    }

}
