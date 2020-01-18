<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
        'social_id',
        'type', //1wechat 2facebook 3github
        'user_id',
        'name',
        'avatar',
    ];

    const TYPE_WECHAT = 1;
    const TYPE_FACEBOOK = 2;
    const TYPE_GITHUB = 3;
    // A person is assigned a unique page-scoped ID (PSID) for each Facebook Page they start a conversation with. The PSID is used by your Messenger bot to identify a person when sending messages.
    const TYPE_FB_PSID = 4;

    const TYPES = ['None', '微信', 'Facebook', 'Github', 'PSID'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
