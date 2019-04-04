<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Book extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'type',//0同步教材 1外交教材
        'publisher',//出版社
        'path',//XXX.pdf
        'page',//页数
    ];
    const SYNC = 0; //同步教材
}
