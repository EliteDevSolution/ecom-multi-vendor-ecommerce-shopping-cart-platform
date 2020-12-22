<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function orderpayment() {
      return $this->hasOne('App\Orderpayment');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }
}
