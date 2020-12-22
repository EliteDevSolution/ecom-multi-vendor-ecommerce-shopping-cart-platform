<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    public function orderedproduct() {
      return $this->belongsTo('App\Orderedproduct');
    }
}
