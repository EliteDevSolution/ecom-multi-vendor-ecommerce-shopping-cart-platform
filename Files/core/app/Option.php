<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public function product_attribute() {
      return $this->belongsTo('App\ProductAttribute');
    }
}
