<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
    public function stock_id()
    {
        return $this->hasMany(StockInDetail::class);
    }
}
