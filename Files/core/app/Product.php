<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = [];

    public function vendor() {
      return $this->belongsTo('App\Vendor');
    }

    public function previewimages() {
      return $this->hasMany('App\PreviewImage');
    }

    public function carts() {
      return $this->hasMany('App\Cart');
    }

    public function category() {
      return $this->belongsTo('App\Category');
    }

    public function subcategory() {
      return $this->belongsTo('App\Subcategory');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    public function productreviews() {
      return $this->hasMany('App\ProductReview');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'favorits');
    }

    public function flashinterval() {
      return $this->belongsTo('App\FlashInterval');
    }
}
