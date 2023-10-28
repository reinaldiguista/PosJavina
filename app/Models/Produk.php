<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk_master';
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
}
