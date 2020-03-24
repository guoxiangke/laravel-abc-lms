<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Zoom extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;
    protected $fillable = [
        'email',
        'password',
        'pmi', //https://zoom.us/j/9292858384
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id', 'zoom_id');
    }
}
