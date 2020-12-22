<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public function options() {
      return $this->hasMany('App\Option');
    }
}
