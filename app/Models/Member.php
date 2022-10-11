<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function transaction()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function type()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type', 'id');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }
}
