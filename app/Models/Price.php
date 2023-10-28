<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'price';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function sku()
    {
        return $this->belongsTo(ProdukNew::class, 'sku', 'sku');
    }
}
