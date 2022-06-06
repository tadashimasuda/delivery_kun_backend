<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarningsIncentivesSheet extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = ['id','user_id','title','earnings_incentives'];
    protected $casts = [
        'earnings_incentives' => 'json'
    ];
}
