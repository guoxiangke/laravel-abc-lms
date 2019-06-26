<?php

namespace App\Models;

use App\Traits\HasPriceField;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

//$table->string('image')->nullable();

class Product extends Model implements HasMedia
{
    use SoftDeletes;
    use HasPriceField;
    use HasMediaTrait;
    protected $fillable = [
        'name',
        'description',
        'price',
        'remark',
    ];

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('images') //产品图片
            // ->useDisk('s3')
            ->singleFile();
    }
}
