<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashInterval extends Model
{
    public function products() {
      return $this->hasMany('App\Product');
    }
}
