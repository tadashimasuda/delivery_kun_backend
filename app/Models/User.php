<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'social_name',
        'social_id',
        'img_path',
        'prefecture_id',
        'vehicle_model'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function status()
    {
        return $this->hasMany(Status::class);
    }

    public function order()
    {
        return $this->hasMany(OrderDemaecan::class);
    }

    public function earnings_incentives_sheet()
    {
        return $this->hasMany(EarningsIncentivesSheet::class);
    }
}
