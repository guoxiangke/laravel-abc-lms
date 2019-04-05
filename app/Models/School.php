<?php

namespace App\Models;

use App\User;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class School extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'name',
        'image',
        'remark',
    	'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'user_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
