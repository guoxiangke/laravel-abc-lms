<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', // 关联用户 可为空
        'school_id', //NULL为自由职业freelancer
        'zoom_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }

    public function zoom()
    {
        return $this->hasOne(Zoom::class, 'id', 'zoom_id');
    }
}
