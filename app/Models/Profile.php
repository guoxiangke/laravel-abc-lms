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
    	'user_id',
    	'name',
    	'sex',
    	'birthday',
    	'telephone',
        'recommend_uid',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'birthday'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @doc User 有profle， 学生和老师、代理也可以有一个proflie
     * @useage
     * $model = App\Models\Profile::find(1)->model;
     * $modelId = $model->getKey(); // 1
     * $modelId = $model->getKeyName(); // id
     * $class = (new \ReflectionClass($model))->getShortName(); //"User"
     * $class = (new \ReflectionClass($model))->getName(); //"App\User"
     */
    // public function model() {
    //     return $this->hasOne($this->target_type,'id','target_id' );
    // }
    
    //name sex birthday telephone 管理人员姓名 性别 年龄 手机等
    public function contact()
    {
        return $this->hasOne(Contact::class);
    }


    // agency 
    public function recommend()
    {
        return $this->hasOne(User::class, 'id', 'recommend_uid');
    }
}
