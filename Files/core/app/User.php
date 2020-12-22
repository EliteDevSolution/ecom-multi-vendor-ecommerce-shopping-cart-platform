<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orderpayments() {
      return $this->hasMany('App\Orderpayment');
    }

    public function orderedproducts() {
      return $this->hasMany('App\Orderedproduct');
    }

    public function productreviews() {
      return $this->hasMany('App\ProductReview');
    }

    public function products() {
        return $this->belongsToMany('App\Product', 'favorits');
    }

    public function orders() {
      return $this->hasMany('App\Order');
    }


}
