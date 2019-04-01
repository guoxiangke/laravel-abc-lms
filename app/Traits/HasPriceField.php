<?php

namespace App\Traits;

trait HasPriceField
{
    public function getPriceAttribute($value){
      return $value/100;
    }

    public function setPriceAttribute($value){
      $this->attributes['price'] = $value*100;
    }
}
