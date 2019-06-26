<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasPriceField;
use App\User;

class Bill extends Model
{
    use SoftDeletes;
    use HasPriceField;
    const TYPES =[
        '收入',//0
        '支出',//1
    ];
    //0:append 1:approved已成交/入账
    const STATUS =['Append','Approved'];
    protected $fillable = [
        'type',
        'user_id',
        'order_id',
        'price',//amount
        'paymethod_type',
        'status',
        'remark',//经手人 //收/付款时间etc.
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
