<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Contact extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
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
