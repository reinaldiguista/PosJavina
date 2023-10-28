<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukNew extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function listproducttransaction()
    {
        return $this->hasMany(ListProductTransaction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori', 'id');
    }

    public function price()
    {
        // return $this->belongsTo(Price::class, 'sku', 'sku_produk');

        return $this->belongsTo(Price::class, 'sku', 'sku_produk')->withDefault([
            'harga_1' => 0,
            'harga_2' => 0,
            'harga_3' => 0
        ]);
    }
    public function priceRules()
    {
        // return $this->belongsTo(Price::class, 'sku', 'sku_produk');

        return $this->belongsTo(PriceRules::class, 'kategori', 'kategori_produk')->withDefault([
            'limit_1' => 0,
            'limit_2' => 0
        ]);
    }

}
