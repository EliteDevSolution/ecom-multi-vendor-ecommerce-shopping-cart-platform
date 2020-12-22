<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    public function contactnumbers() {
      return $this->hasMany('App\ContactNumber');
    }

    public function previewimages() {
      return $this->hasMany('App\PreviewImage');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function promoterequests() {
      return $this->hasMany('App\PromoteRequest');
    }

    public function favorits() {
      return $this->hasMany('App\Favorit');
    }

    public function reports() {
      return $this->hasMany('App\Report');
    }
}
