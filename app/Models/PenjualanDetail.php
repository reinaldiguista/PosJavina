<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'list_product_transaction';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function produk()
    {
        return $this->hasOne(ProdukNew::class, 'id', 'product_id');
    }
}
