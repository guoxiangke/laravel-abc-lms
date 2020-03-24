<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Contact extends Model
{
    use SoftDeletes;
    
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

    // 'skype','wechat/qq','facebook','.',
    const TYPES = [
        'skype',
        'wechat/qq',
        'facebook',
    ];
    protected $fillable = [
        'profile_id',
        'type',
        'number',
        'remark',
    ];
}
