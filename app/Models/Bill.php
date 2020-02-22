<?php

namespace App\Models;

use App\User;
use App\Traits\HasPriceField;
use Laravelista\Comments\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;
    use HasPriceField;
    use Commentable;
    const TYPES = [
        '收入', //0
        '支出', //1
    ];
    //currencies
    const CURRENCIES = [
        '$', //美元
        '¥', //人民币
        '₱', //比索
    ];
    //0:append 1:approved已成交/入账
    const STATUS = ['Append', 'Approved'];
    protected $fillable = [
        'type',
        'user_id',
        'order_id',
        'price', //amount
        'currency',
        'paymethod_type',
        'status',
        'remark', //经手人 //收/付款时间etc.
        'created_at', //入账日期
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
