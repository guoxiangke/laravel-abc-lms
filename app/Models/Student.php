<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\User;
use App\Models\Agency;
use App\Models\Profile;
use App\Models\Order;

class Student extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
    protected $fillable = [
        'user_id',
        'grade',
        'level',
        'book_id',
        // 'agency_uid', //@see profile 'recommend_uid',
        'remark',
        'name',//英文名字
    ];
    //0代表幼儿园 1-9年级 高中1-3(10-12) 大学1-4(13-16) 17成人
    const GRADES =[
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
}
