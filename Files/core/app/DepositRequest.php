<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositRequest extends Model
{
    public function vendor() {
      return $this->belongsTo('App\Vendor');
    }

    public function gateway() {
      return $this->belongsTo('App\Gateway');
    }
}
