<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListProductTransaction extends Model
{
    use HasFactory;

    protected $table = 'list_product_transaction';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id', 'id');
    }
}
