<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Profile extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
    const SEXS =[
        '女',
        '男'
    ];
    protected $fillable = [
        //$contact = $school->user->profiles->first()->contacts->first();
    	'user_id',//可为空，一个用户可以有多个profiles，一个profile也可以对应多个contacts
    	'name',
    	'sex',
    	'birthday',
    	'telephone',//唯一，用于登陆！
        'recommend_uid',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'birthday'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @doc User 有profles， 学生和老师、代理也可以有一个proflie
     * @useage
     * $model = App\Models\Profile::find(1)->contacts->first();
     * $modelId = $model->getKey(); // 1
     * $modelId = $model->getKeyName(); // id
     * $class = (new \ReflectionClass($model))->getShortName(); //"User"
     * $class = (new \ReflectionClass($model))->getName(); //"App\User"
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }


    // agency 
    public function recommend()
    {
        return $this->hasOne(Profile::class, 'user_id', 'recommend_uid');
    }

    
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function agency()
    {
        return $this->hasOne(Agency::class, 'user_id', 'user_id');
    }


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
