<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListProductTransaction extends Model
{
    use HasFactory;

    protected $table = 'list_product_transaction_master';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Pembelian::class, 'transaction_id', 'id');
    }
    
    public function produk()
    {
        return $this->belongsTo(ProdukNew::class, 'product_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'customer_id', 'id');
    }   
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }   
}
