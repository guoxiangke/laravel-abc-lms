<?php
//Only gen by Console.
namespace App\Models;

use App\User;
use App\Models\Teacher;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Support\Facades\Storage;


class ClassRecord extends Model implements AuditableContract, HasMedia, Commentable
{
	use SoftDeletes;
	use Auditable;
    use HasComments;
    use HasMediaTrait;

    const DISK = 'spaces';//ClassRecord::DISK upyun
    public function registerMediaCollections()
    {
        $this->addMediaCollection('mp3')
            ->useDisk(self::DISK)
            ->singleFile();
            //todo acceptsFile('mp3')
        $this->addMediaCollection('mp4')
            ->useDisk(self::DISK)
            ->singleFile();
    }

    const EXCEPTION_TYPES =[
        '正常',//0
        '学生请假了',//1
        '老师请假了',//2
        '学生旷课了',//学生异常 3
        '老师异常标记',//老师异常,不给老师算课时，需要给学生补课 4
    ];

    const EXCEPTION_TYPES_EN =[
        'Normal',//0
        '❎AOL-by-Student',//1
        '❎AOL-by-Teacher',//2
        'Absent-by-Student',//学生异常 3
        '❎❎❎',//Absent-by-Teacher 老师异常,不给老师算课时，需要给学生补课 4
    ];

    //@see ClassRecordPolicy 谁可以列表查看
    const ALLOW_LIST_ROLES =['agency', 'teacher', 'student'];

    const NO_EXCEPTION = 0;
    const NORMAL_EXCEPTION_STUDENT = 1;
    const NORMAL_EXCEPTION_TEACHER = 2;
    const EXCEPTION_STUDENT = 3;
    const EXCEPTION_TEACHER = 4;

    //是否需要补课
    const EXCEPTIONS_NEED_PATCH = [1,2,4];
    const EXCEPTIONS_NONEED_PATCH = [0,3];

    protected $fillable = [
        'rrule_id',
        'order_id',
        'user_id',//'student_uid',
        'teacher_uid',
        'agency_uid',
        'remark', //book, page, mistake, ...
        //默认=1/ture，todo: 如果有任何异常，标记为false，不作为已上课时总数计算 $order->AllDoneClassRecordes('weight')->sum()
        'weight',
        // 默认为0，正常
        // 学生请假 1 需要补课，标记 weight = 0，不作为已上课时总数计算 
        // 老师请假 2 需要补课，标记 weight = 0，不作为已上课时总数计算 
        // 学生异常请假 3  计算课时 标红 🙅不需要补课
        // 老师异常 4  计算课时 标黄 | 需要补课， 标记 weight = 0，不作为已上课时总数计算 
        'exception',
        'generated_at', //特别有用，自动生成记录时，唯一确认是否新建
    ];

    //更新Exception时，需要同步更新weight
    public function setExceptionAttribute($value)
    {
        //需要补课的，标记为false，即不计算在课程总数内
        $this->attributes['weight'] = true; //weight默认是1
        //只有学生旷课时，标记为weight=1，即需要计算在内, 其他为0
        if(in_array($value,[1,2,4])){
            $this->attributes['weight'] = false;
            \Log::error(__FUNCTION__,['ClassRecord weight updated by exception changed']);
        }
        $this->attributes['exception'] = $value;
    }

    /**
    * Scope a query to only include active users.
    * 不🙅需要补课
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    //用于已上课时计算
    public function scopeNoPack($query)
    {
        return $query->whereIn('exception', self::EXCEPTIONS_NONEED_PATCH);//0,3
    }


    public function scopeByException($query, $exception = self::NO_EXCEPTION)
    {
        return $query->where('exception', $exception);
    }

    /**
    * Scope a query to only include active users.
    * 需要补课, 三种正常请假模式！
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeExceptions($query)
    {
        return $query->whereIn('exception', self::EXCEPTIONS_NEED_PATCH);//1,2,4
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'generated_at'];

    // $order->teacher->teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_uid');
    }
    //Teacher Model
    public function teacherModel()
    {
        return $this->belongsTo(Teacher::class, 'teacher_uid', 'user_id');
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_uid');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rrule()
    {
        return $this->belongsTo(Rrule::class);
    }

    public function getMp3Attribute(){
        // https://dxjy.sfo2.digitaloceanspaces.com//media/
        // https://dxjy.sfo2.digitaloceanspaces.com/media/
        return substr($this->getFirstMediaUrl('mp3'), 1);
    }

    public function getMp4Attribute(){
        return substr($this->getFirstMediaUrl('mp4'), 1);
    }
    
    public function getUrl($type='mp3'){
        return Storage::disk(ClassRecord::DISK)->temporaryUrl($this->{$type}, now()->addMinutes(30));
    }

}
