<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Zoom extends Model implements AuditableContract
{
    use SoftDeletes;
    use Auditable;
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
