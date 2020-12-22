<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
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

        public function products() {
          return $this->hasMany('App\Product');
        }

        public function orderedproducts() {
          return $this->hasMany('App\Orderedproduct');
        }

        public function deposits() {
          return $this->hasMany('App\Deposit');
        }

        public function depositrequets() {
          return $this->hasMany('App\DepositRequest');
        }

        public function withdraws() {
          return $this->hasMany('App\Withdraw');
        }

        public function transactions() {
          return $this->hasMany('App\Transaction');
        }
}
