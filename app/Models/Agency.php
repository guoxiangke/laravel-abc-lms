<?php

namespace App\Models;

use App\Traits\HasSchemalessAttributes;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelista\Comments\Commentable;

class Agency extends Model
{
    use SoftDeletes;
    use Commentable;
    use HasSchemalessAttributes;
    // todo 应用以下属性
    const EXTRA_ATTRIBUTES = ['slogan', 'introduction', 'address', 'tel'];
    public $casts = [
        'extra_attributes' => 'array',
    ];
    // logo
    // todo 代理类型的应用！
    const TYPES = [
        '个人代理', //银牌🥈
        '机构代理', //金牌🏅️
        //add  more
    ];

    protected $fillable = [
        'user_id',
        'type',
        'discount', //0-99+%折扣
        'name', //机构名字
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

    // Agency::getAllReference()
    // ProfileNameByUid() for input form.
    public static function getAllReference()
    {
        return self::with('profiles')->get()->pluck('profiles.0.name', 'user_id')->filter()->toArray();
    }
}
