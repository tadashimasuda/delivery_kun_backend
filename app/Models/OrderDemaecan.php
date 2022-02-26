<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDemaecan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'earnings_incentive',
        'earnings_base',
        'earnings_total',
        'prefecture_id',
    ];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
}
