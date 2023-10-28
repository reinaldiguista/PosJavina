<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePay extends Model
{
    use HasFactory;

    protected $table = 'invoice_pay';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
    public function invoice_pay()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
