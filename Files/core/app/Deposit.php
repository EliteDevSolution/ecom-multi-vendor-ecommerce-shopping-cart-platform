<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
    public function gateway()
    {
        return $this->belongsTo('App\Gateway');
    }
}
