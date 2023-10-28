<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRules extends Model
{
    use HasFactory;

    protected $table = 'price_rules';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function sku()
    {
        return $this->belongsTo(ProdukNew::class, 'kategori', 'kategori');
    }
}
