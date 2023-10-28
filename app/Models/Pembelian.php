<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'transaction_master';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'customer_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    
    public function transaction()
    {
        return $this->hasMany(ListProductTransaction::class);
    }
}
