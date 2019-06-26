<?php

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Rrule extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'start_at'];

    // @see https://jakubroztocil.github.io/rrule/
    // DTSTART:20190322T000000Z
    // RRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=MO,TU,WE,TH,FR,SA,SU
    protected $fillable = [
        'string', //ori_sting withour first Line
        // 'text',//TransformerText
        'type', //'AOL', 0 'SCHEDULE',1
        'order_id',
        'start_at',
        // 'period',//'次数'
    ];

    const TYPE_AOL = 0;
    const TYPE_SCHEDULE = 1;
    const TYPES = ['AOL', 'Schedule'];

    const BYDAYS = [
        'SU'=> 0,
        'MO'=> 1,
        'TU'=> 2,
        'WE'=> 3,
        'TH'=> 4,
        'FR'=> 5,
        'SA'=> 6,
    ];

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    //"MO,WE,FR" => '1,3,5'
    public function toDayOfWeek()
    {
        $byDayString = $this->getRrule('BYDAY');
        $byDayArray = explode(',', $byDayString);
        foreach ($byDayArray as $key => $value) {
            $byDayArray[$key] = self::BYDAYS[$value];
        }

        return $byDayArray;
    }

    public function getRule()
    {
        $timezone = config('app.timezone');
        $rruleString = $this->string;
        $startDate = $this->start_at;
        $rule = new \Recurr\Rule($rruleString, $startDate, null, $timezone);

        return $rule;
    }

    public function toText()
    {
        $transformer = new \Recurr\Transformer\TextTransformer();
        $rruleText = $transformer->transform($this->getRule());

        return $rruleText;
    }

    // get BYDAY as dayOfWeek
    public function getRrule($key = false)
    {//'BYDAY'
        // RRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=MO
        $rruleStrings = explode(';', $this->string);
        $rruleArray = [];
        foreach ($rruleStrings as $value) {
            $keyvalue = explode('=', $value);
            $rruleArray[$keyvalue[0]] = $keyvalue[1];
        }
        if ($key) {
            return $rruleArray[$key];
        }

        return $rruleArray;
    }

    // public function getRruleCollection(){
    //     $rule = $this->getRule();

    //     $transformer = new \Recurr\Transformer\ArrayTransformer();
    //     $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
    //     $transformerConfig->enableLastDayOfMonthFix();
    //     $transformer->setConfig($transformerConfig);
    //     $rruleCollection = $transformer->transform($rule);
    //     return  $rruleCollection;
    // }
    // public static function  getRruleCollection
    public static function transCollection(\Recurr\Rule $rule)
    {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);

        return $transformer->transform($rule);
    }

    public static function transArray(\Recurr\Rule $rule)
    {
        return static::transCollection($rule)->toArray();
    }

    public static function transByStart(\Recurr\Rule $rule)
    {
        return static::transCollection($rule)->map(function ($recurrence) {
            return $recurrence->getStart()->format('Y-m-d H:i:s');
        });
    }

    //0:arrayToSave 1:collection
    public static function buildRrule($value, $returnRruleCollection = 0)
    {
        $timezone = config('app.timezone');
        // DTSTART;TZID=Asia/Hong_Kong:20190330T180000
        // DTSTART:20190330T180000Z
        // RRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU

        // https://www.kanzaki.com/docs/ical/exrule.html
        // EXRULE:FREQ=WEEKLY;COUNT=4;INTERVAL=2;BYDAY=TU,TH
        // https://www.kanzaki.com/docs/ical/exdate.html
        // EXDATE:19960402T010000Z,19960403T010000Z,19960404T010000Z

        // $value = "DTSTART:20190330T180000\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU";

        // @see https://stackoverflow.com/questions/5053373/explode-a-string-by-r-n-n-r-at-once
        // dd($value);
        $rrules = preg_split('/\r\n?|\n/', $value); // $rrules = explode(PHP_EOL, $value);

        $startDateString = explode(':', $rrules[0])[1];

        $startDate = Carbon::createFromFormat('Ymd\THis\Z', $startDateString, $timezone);
        $rruleString = substr($rrules[1], 6); //remove  RRULE:
        // $timezone    = 'Asia/Hong_Kong';
        $rule = new \Recurr\Rule($rruleString, $startDate, null, $timezone);

        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);
        $rruleCollection = $transformer->transform($rule);
        if ($returnRruleCollection) {
            return  $rruleCollection;
        }

        return [
            'start_at' => $startDate,
            // 'text' => $rruleText, //TransformerText
            'string' => $rruleString, //oriString
            // 'period' => $rruleCollection->count(),
        ];
    }

    //判断当天是否有课
    public function isToday()
    {
        return collect($this->transArray()->toArray())
            ->filter(function ($recurrence) {
                if ($recurrence->getStart()->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                    return true;
                }

                return false;
            })->count();
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    /**
     * all classRecords
     * 🙅不需要补课/即顺延的课程
     * $rrule->classRecords()->absent()->count();
     * 需要补课/即顺延的课程
     * $rrule->classRecords()->exceptions()->count();
     */
    public function classRecords()
    {
        return $this
                ->hasMany(ClassRecord::class);
    }
}
