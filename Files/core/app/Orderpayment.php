<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderpayment extends Model
{
    protected $guarded = [];

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function order() {
      return $this->belongsTo('App\Order');
    }

    public function gateway() {
      return $this->belongsTo('App\Gateway');
    }
}
