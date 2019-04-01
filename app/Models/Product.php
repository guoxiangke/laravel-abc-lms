<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasPriceField;
class Product extends Model
{
	use SoftDeletes;
    use HasPriceField;
    protected $fillable = [
    	'name',
    	'description',
    	'price',
    	'image',
    	'remark'
    ];
}
