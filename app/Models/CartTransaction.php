<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartTransaction extends Model
{
    use HasFactory;

    protected $table = 'cart_transaction';
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
    public function cart()
    {
        return $this->belongsTo(cart::class, 'cart_id', 'id');
    }
}
