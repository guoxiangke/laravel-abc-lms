<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
        'social_id',
        'type',//1wechat 2facebook 3github
        'user_id',
    ];

    const TYPE_WECHAT = 1;
    const TYPE_FACEBOOK = 2;
    const TYPE_GITHUB = 3;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
