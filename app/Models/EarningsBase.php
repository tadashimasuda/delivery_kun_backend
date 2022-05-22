<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarningsBase extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','earnings_base'];
}
