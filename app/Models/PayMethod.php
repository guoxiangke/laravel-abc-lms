<?php

namespace App\Models;

use App\User;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PayMethod extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;

    const TYPES = [
        'PayPal',
        'AliPay',
        'WechatPay',
        'Bank',
        'Skype',
    ];

    protected $fillable = [
        'type',
        'number',
        'remark',
        'user_id',
    ];

    // getType = teacher
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
