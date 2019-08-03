<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'class_record_id',
        'task_id',
        'start_time',
        'end_time',
        'path',
    ];

    public function getCdnUrl()
    {
        //'http://updxyy.test.upcdn.net';
        // also can use ClassRecord::DOS_CDN
        return config('upyun.protocol').'://'.config('upyun.domain').$this->path;
    }
}
