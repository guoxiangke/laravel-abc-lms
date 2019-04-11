<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Social extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'social_id',
        'type',//wechat 2facebook
        'user_id',
    ];

    const TYPE_WECHAT = 1;
    const TYPE_FACEBOOK = 2;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
