<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
    use HasFactory;
    
    protected $table = 'stock_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function produk()
    {
        return $this->hasOne(ProdukNew::class, 'id', 'product_id');
    }
}
