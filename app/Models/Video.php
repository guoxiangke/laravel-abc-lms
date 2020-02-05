<?php

namespace App\Models;

use App\User;
use Mtvs\EloquentHashids\HasHashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mtvs\EloquentHashids\HashidRouting;

class Video extends Model
{
    use HasHashid, HashidRouting;
    use SoftDeletes;

    protected $fillable = [
        'class_record_id',
        'task_id',
        'start_time',
        'end_time',
        'path',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
