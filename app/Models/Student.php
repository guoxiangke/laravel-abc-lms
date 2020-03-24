<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelista\Comments\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;


class Student extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;
    use Commentable;

    protected $fillable = [
        'user_id',
        'grade',
        'level',
        'book_id',
        // 'agency_uid', //@see profile 'recommend_uid',
        'remark',
        'name', //英文名字
        'creater_uid', // edit own student's profile!
    ];
    const ALLOW_LIST_ROLES = ['agency', 'teacher', 'student']; //indexByRole permission
    //0代表幼儿园 1-9年级 高中1-3(10-12) 大学1-4(13-16) 17成人
    const GRADES = [
        '幼儿园',
        '1年级',
        '2年级',
        '3年级',
        '4年级',
        '5年级',
        '6年级',
        '7年级',
        '8年级',
        '9年级',
        '高一',
        '高二',
        '高三',
        '大一',
        '大二',
        '大三',
        '大四',
        '成人',
    ];

    public function user()
    {
        // return $this->hasOne(User::class, 'id', 'user_id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function creater()
    {
        return $this->belongsTo(User::class, 'creater_uid', 'id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getAllReference()
    {
        return self::with('profiles')->get()->pluck('profiles.0.name', 'user_id')->filter()->toArray();
    }
}
