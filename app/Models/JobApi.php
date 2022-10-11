<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApi extends Model
{
    use HasFactory;

    protected $table = 'job_api';
    protected $primaryKey = 'id';
    protected $guarded = [];

}
