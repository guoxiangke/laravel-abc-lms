<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

use Illuminate\Database\Eloquent\SoftDeletes;
class Agency extends Model
{
    use SoftDeletes;

    const TYPES =[
        '银牌代理',
        '金牌代理',
        //add  more
    ];

    protected $fillable = [
    	'user_id',
    	'agency_uid',
        'discount', //0-99+%折扣
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function reference()
    {
        return $this->hasOne(User::class, 'id', 'agency_uid');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
