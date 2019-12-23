<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteType extends Model
{
    // fillable 与 guarded 只限制了 create 方法，而不会限制 save
    // https://learnku.com/laravel/wikis/16126
    protected $guarded = [];

    //get types bind of  model
    public static function get($model)
    {
        return static::where('votable_type', get_class($model))
            ->get();
    }
}
