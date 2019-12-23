<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    // fillable 与 guarded 只限制了 create 方法，而不会限制 save
    // https://learnku.com/laravel/wikis/16126
    protected $guarded = [];

    // Useage: ★★★☆☆
    // $classRecord = App\Models\classRecord::find(7154);

    // VoteType::get($classRecord);

    // $voteType =  App\Models\voteType::find(3);
    // Vote::set($voteType, $classRecord, 2);
    // Vote::get($voteType, $classRecord);

    public static function set($voteType, $model, $value = 1)
    {
        //如果类型不匹配的vote，忽略
        // get a model, check if voteable?
        if ($voteType->votable_type == get_class($model)) {
            $userId = auth()->id();
            $userId = 1; //for tinker test
            //如果5星，却大于5的话
            if ($value > $voteType->type) {
                return;
            }
            //一个用户对一个model最多只有一个选择
            $vote = static::firstOrNew([
                // 'votable_value' => $value,//max value check!!!
                'user_id' => $userId, //currnet user
                'vote_type_id' => $voteType->id,
                'votable_id' => $model->id,
                'votable_type' => $voteType->votable_type,
            ]);
            $vote->votable_value = $value;

            return $vote->save();
        }
    }

    //get result of one type vote
    public static function get($voteType, $model)
    {
        return static::where('vote_type_id', $voteType->id)
            ->where('votable_id', $model->id)
            ->where('votable_type', $voteType->votable_type)
            ->sum('votable_value');
    }
}
