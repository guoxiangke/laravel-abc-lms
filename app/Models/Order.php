<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use App\Traits\HasPriceField;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravelista\Comments\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Order extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
    use HasPriceField;
    use Commentable;
    const LIST_BY = ['index', 'trail', 'overdue', 'pause', 'done', 'trash', 'all'];
    // 0 订单作废 1 订单正常* 2 订单完成  3 订单暂停上课  4 订单过期
    const STATU_TRASH = 0;
    const STATU_ACTIVE = 1;
    const STATU_COMPLETED = 2;
    const STATU_PAUSE = 3;
    const STATU_OVERDUE = 4;
    const STATUS = [
        '作废',
        '正常',
        '完成',
        '暂停',
        '过期',
    ];

    protected $fillable = [
        'user_id', //'student_id',
        'teacher_uid',
        'agency_uid',
        'book_id', //todo
        'product_id',
        'price', //'单位yuan'
        'period', //'课时' 20
        'expired_at', //有效期
        'status', //default 1
        'remark',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'expired_at'];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    public function getTitleAttribute()
    {
        //190101-studentName-TeacherName-AgencyName-20-159
        $title = $this->created_at->format('ymd')
                .'-'.$this->user->profiles->first()->name
                .'-'.$this->teacher->profiles->first()->name
                .'-'.$this->agency->profiles->first()->name
                .'-'.$this->period;

        //学生和老师不显示价格
        if (Auth::user() && ! Auth::user()->hasAnyRole(['student', 'teacher'])) {
            $title .= '-'.$this->price;
        }

        return $title;
    }

    //一个订单有很多rrule 每个rrule有很多classRecords
    //获取订单的所有计划，包括请假计划、上课计划（可以包含多个）
    public function schedules()
    {
        return $this
                ->hasMany(Rrule::class, 'order_id', 'id')
                ->where('rrules.type', Rrule::TYPE_SCHEDULE);
    }

    //请假计划
    //https://laracasts.com/discuss/channels/laravel/where-in-hasmany-relation
    // https://laravel.com/docs/5.3/eloquent-relationships#querying-relationship-existence
    public function aols()
    {
        return $this
                ->hasMany(Rrule::class, 'order_id', 'id')
                ->where('rrules.type', Rrule::TYPE_AOL)
                ->where('rrules.status', Rrule::STATU_ACTIVE);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function rrules()
    {
        return $this->hasMany(Rrule::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'agency_uid');
    }

    // $order->teacher->teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_uid');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', static::STATU_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == static::STATU_ACTIVE;
    }

    /**
     * 所有de上课计划的上课历史记录/包含异常.
     */
    public function classRecords()
    {
        $classRecords = new Collection;
        foreach ($this->schedules as $rrule) {
            $classRecords = $classRecords->merge($rrule->classRecords);
        }

        return  $classRecords;
    }

    /**
     * 已上课时
     * exception = [0,3]
     * 舍弃：$rrule->classRecords()->noPack().
     */
    public function classDoneRecords()
    {
        return $this->classRecords()->filter(function ($classRecord) {
            return in_array($classRecord->exception, ClassRecord::EXCEPTIONS_NONEED_PATCH);
        });

        // $classRecords = new Collection;
        // foreach ($this->schedules as $rrule) {
        //    $classRecords = $classRecords->merge($rrule->classRecords()->noPack()->get());
        // }
        // return  $classRecords;
    }

    /**
     * 老师已请假    学生已请假   学生旷课即作废
     * 学生旷课即作废课时计算 scopeByException(ClassRecord::EXCEPTION_STUDENT)
     * 舍弃：$rrule->classRecords()->byException($exceptionInt).
     */
    public function classRecordsAolBy($exception = 'absent')
    {
        if ($exception == 'teacher') {
            $exceptionInt = ClassRecord::NORMAL_EXCEPTION_TEACHER;
        }
        if ($exception == 'student') {
            $exceptionInt = ClassRecord::NORMAL_EXCEPTION_STUDENT;
        }
        if ($exception == 'absent') {
            $exceptionInt = ClassRecord::EXCEPTION_STUDENT;
        }
        if ($exception == 'exception') {
            $exceptionInt = ClassRecord::EXCEPTION_TEACHER;
        } //老师异常：龙卷风

        return $this->classRecords()->filter(function ($classRecord) use ($exceptionInt) {
            return  $classRecord->exception == $exceptionInt;
        });

        // $classRecords = new Collection;
        // foreach ($this->schedules as $rrule) {
        // // dd($rrule->classRecords()->byException($exceptionInt)->get());
        //    $classRecords = $classRecords->merge($rrule->classRecords()->byException($exceptionInt)->get());
        // }
        // return  $classRecords;
    }

    /**
     * 判断某天是否有课！！
     * hasClassesByDay / classesToday
     * 找出今天需要上的2节课 的时间H:i
     * 然后通过rrule的时间找到对应的rrule_id 然后创建 classRecord.
     */
    public function hasClass(Carbon $byDay = null)
    {
        $history = true;
        if (! $byDay) {
            $byDay = Carbon::now();
            $history = false;
        }

        return $this->regenRruleSchedule($history)->filter(function ($startDateString) use ($byDay) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $startDateString)->format('Y-m-d') == $byDay->format('Y-m-d');
        })->map(function ($dateString) {
            return substr($dateString, 11, 5); //H:i
        })->toArray();
    }

    //加上 请假次数 作为schedule rule!
    public function regenRruleSchedule($history = false)
    {
        //not only for the first 有效上课计划
        // $rrule = $this->schedules->first();
        $rruleSchedulesCollections = new Collection;
        $today = Carbon::now();
        foreach ($this->schedules as $rrule) {
            //doneExceptDatesByThisRule
            $doneCount = $this->classDoneRecords()->filter(function ($item) use ($rrule) {
                if ($item->rrule_id == $rrule->id) {
                    return true;
                }
            })->count();
            /* @var $rule /Recurr/Rule */
            $rule = $rrule->getRule($today->format('Y-m-d').$rrule->start_at->format('H:i'), $doneCount);
            $aols = $this->regenAolsSchedule()->toArray();
            $count = 0; //请假次数
            $allDateStringCollection = Rrule::transByStart($rule);
            $aolsForThisRule = [];
            foreach ($aols as $dateString) {
                // 该请假日期必须在XX里面 && substr($dateString, 11, 5);//H:i
                //in_array($dateString, $allDateStringCollection->toArray()) &&
                if (substr($dateString, 11, 5) == $rrule->start_at->format('H:i')) {
                    $count++;
                    $aolsForThisRule[] = $dateString;
                }
            }
            // dd(Rrule::transByStart($rule), $aols, $count, $aolsForThisRule);
            $rule->setExDates($aolsForThisRule);
            //不是每一个上课计划都要扣除 请假计划次数。
            //而是 对应的时间 才扣除！！
            $rule->setCount($rule->getCount() + $count);

            $rruleSchedulesCollections = $rruleSchedulesCollections->merge(
                Rrule::transByStart($rule)->filter(function ($items) use ($history) {
                    if ($history) {
                        return true;
                    }
                    //减去历史的,包括今天（用来判断 classesToday）
                    return new \DateTime($items) >= now();
                })
            );
        }

        return $rruleSchedulesCollections;
    }

    /**
     * 大于今天的请假计划 + 历史记录的（学生和老师）正常请假计划.
     */
    public function regenAolsSchedule()
    {
        $aolsDateStringCollection = $this->getAllAols()->filter(function ($items) {
            //减去历史的
            return Carbon::createFromFormat('Y-m-d H:i:s', $items) >= Carbon::now();
        });

        //region 第二部分 for 已生成的记录，又标记为XXXX ！改变历史 把请假的天数+进去！
        $classRecordNormalExecptionAddToAol = new Collection;

        foreach ($this->schedules as $rrule) {
            foreach ($rrule->classRecords()->exceptions()->get() as  $classRecord) {
                $classRecordNormalExecptionAddToAol->push($classRecord->generated_at->format('Y-m-d H:i:s'));
            }
        }
        //endregion

        $aolsDateStringCollection = $aolsDateStringCollection->merge($classRecordNormalExecptionAddToAol);

        return $aolsDateStringCollection;
    }

    /**
     * 实际请假
     * 可能有的请假不在计划范围中呢(即多了)
     * 也有可能少了！比如就到某天截止，而后面还有请假
     * 各自有效请假次数/时间 = 最远schedule ∩ reAol
     * 最远schedule = rruleEndDate=expired_at （或错略计算为2倍课时数）.
     */
    // public function getDiffAols()
    // {
    //     //交集数量 = 请假次数 $intersectCounts = $this->getAols()->count();
    //     $aolsCollection = $this->getAllAols();
    //     $schedulesCollection = $this->getAllSchedules();
    //     // dd($aolsCollection,$schedulesCollection);
    //     return $schedulesCollection->intersect($aolsCollection);
    // }
    public function reDiffAols()
    {
        /**
         * $aolByRules =
         * array:1 [▼
         * "18:00" => DateTime @1553940000 {#830 ▶}
         * ].
         */
        // $aolsLastByRule = new Collection; 每一个请假计划的最后一个有效期
        $aolsLastByRule = [];

        $AllValidAols = new Collection;
        foreach ($this->aols as $rrule) {
            $rule = $rrule->getRule();
            $aolsCollection = Rrule::transCollection($rule);

            $lastStartDateTime = $aolsCollection->last()->getEnd();
            $aolsLastByRule[$lastStartDateTime->format('H:i')] = $lastStartDateTime;

            // get all 有效aol, 截止到有效期！！！
            // 2种可能，
            // 1是3次请假后
            // 2是30000次请假， 此时截止到有效期（大于有效期的都去除）
            $thisValidAols = $aolsCollection->filter(function ($recurrence) {
                return $recurrence->getStart() <= $this->expired_at;
            });
            $AllValidAols = $AllValidAols->merge($thisValidAols);
        }

        // get total all farSchedules
        $farSchedules = new Collection;
        foreach ($this->schedules as $rrule) {
            //如果有 aol end_at
            $rule = $rrule->getRule();
            $key = $rrule->start_at->format('H:i');
            if (isset($aolsLastByRule[$key])) {
                $rule->setUntil($aolsLastByRule[$key]);
                $ruleDates = Rrule::transByStart($rule);
                $farSchedules = $farSchedules->merge($ruleDates);
            } else { //此计划没有对应请假计划， 保留原样
                $ruleDates = Rrule::transByStart($rule);
                $farSchedules = $farSchedules->merge($ruleDates);
            }
        }

        $AllValidAols = $AllValidAols->map(function ($recurrence) {
            return $recurrence->getStart()->format('Y-m-d H:i:s');
        });

        $historyRecords = $this->classRecords()->map(function ($classRecord) {
            return $classRecord->generated_at->format('Y-m-d H:i:s');
        });

        $AllValidAols = $AllValidAols->intersect($farSchedules);
        $AllValidAols = $AllValidAols->diff($historyRecords);

        return $AllValidAols;
    }

    /**
     * 所有请假计划的StartTime[].
     */
    public function getAllAols()
    {
        return $this->getAllStartTime('aols');
    }

    /**
     * 所有上课计划的StartTime[].
     */
    public function getAllSchedules()
    {
        return $this->getAllStartTime('schedules');
    }

    public function getAllStartTime($byType = 'schedules')
    {
        $recurrenceCollections = new Collection;
        $rrules = $this->$byType;
        foreach ($rrules as $rrule) {
            //region schedules
            $rule = $rrule->getRule();
            $recurrenceCollections = $recurrenceCollections->merge(collect(Rrule::transArray($rule)));
        }

        return $recurrenceCollections->map(function ($recurrence) {
            return $recurrence->getStart()->format('Y-m-d H:i:s');
        });
    }
}
