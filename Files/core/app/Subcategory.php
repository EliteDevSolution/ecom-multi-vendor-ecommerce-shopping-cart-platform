<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public function category() {
      return $this->belongsTo('App\Category');
    }

    public function brands() {
      return $this->hasMany('App\Brand');
    }

    public function itemtypes() {
      return $this->hasMany('App\ItemType');
    }

    public function servicetypes() {
      return $this->hasMany('App\ServiceType');
    }

    public function products() {
      return $this->hasMany('App\Product');
    }
}
