<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart_master';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'customer_id', 'id');
    }
    public function produk()
    {
        return $this->belongsTo(ProdukNew::class, 'product_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
}
