<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Prefecture
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\PrefectureFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefecture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prefecture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prefecture query()
 * @mixin \Eloquent
 */
class Prefecture extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
