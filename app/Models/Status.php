<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $dates = ['finish_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'days_earnings_total',
        'actual_cost',
        'days_earnings_qty',
        'prefecture_id',
        'finish_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
