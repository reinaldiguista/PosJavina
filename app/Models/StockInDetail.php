<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInDetail extends Model
{
    use HasFactory;

    protected $table = 'stock_in_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function stock_in()
    {
        return $this->belongsTo(StockIn::class);
    }
}
