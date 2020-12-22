<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderedproduct extends Model
{
    protected $guarded = [];

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function vendor() {
      return $this->belongsTo('App\Vendor');
    }

    public function product() {
      return $this->belongsTo('App\Product');
    }

    public function order() {
      return $this->belongsTo('App\Order');
    }

    public function refund() {
      return $this->hasOne('App\Refund');
    }
}
