<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use SoftDeletes;

    const TYPES = [
        '银牌代理',
        '金牌代理',
        //add  more
    ];

    protected $fillable = [
        'user_id',
        'type',
        'discount', //0-99+%折扣
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function students()
    {
        return $this
                ->hasMany(Profile::class, 'recommend_uid', 'user_id');
    }
}
