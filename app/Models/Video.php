<?php

namespace App\Models;

use Mtvs\EloquentHashids\HasHashid;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HashidRouting;

class Video extends Model
{
    use HasHashid, HashidRouting;

    protected $fillable = [
        'class_record_id',
        'task_id',
        'start_time',
        'end_time',
        'path',
    ];

    public function classRecord()
    {
        return $this->belongsTo(ClassRecord::class);
    }

    public function getCdnUrl()
    {
        //'http://updxyy.test.upcdn.net';
        // also can use ClassRecord::DOS_CDN
        return config('upyun.protocol').'://'.config('upyun.domain').$this->path;
    }
}
