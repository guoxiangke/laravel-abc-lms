<?php

namespace App\Models;

use App\User;
use App\Traits\HasPriceField;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;
    use HasPriceField;
    use HasSchemalessAttributes;

    const EXTRA_ATTRIBUTES = ['passion', 'ontime', 'messenger', 'christ', 'network', 'noisy'];
    public $casts = [
        'extra_attributes' => 'array',
    ];

    protected $fillable = [
        'user_id', // 关联用户 可为空
        'school_id', //NULL为自由职业freelancer
        'zoom_id', //todo delete!!!
        'pmi',
        'price', //rate
        'active', //是否active
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }

    public function getZhumuAttribute()
    {
        return 'https://zhumu.me/j/'.$this->pmi;
    }

    public function paymethod()
    {
        return $this->hasOne(PayMethod::class, 'user_id', 'user_id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'user_id');
    }

    public static function getAllReference()
    {
        return self::with('profiles')->get()->pluck('profiles.0.name', 'user_id')->filter()->toArray();
    }
}
