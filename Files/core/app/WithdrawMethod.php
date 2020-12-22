<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    public function withdraws() {
      return $this->hasMany('App\Withdraw');
    }
}
