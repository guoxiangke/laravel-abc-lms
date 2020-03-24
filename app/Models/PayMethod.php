<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class PayMethod extends Model
{
    use SoftDeletes;
    
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

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
