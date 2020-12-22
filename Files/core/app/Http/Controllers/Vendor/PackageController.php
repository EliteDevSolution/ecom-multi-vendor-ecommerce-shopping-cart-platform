<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package;
use App\Vendor;
use Carbon\Carbon;
use Auth;
use Session;
use App\Transaction;

class PackageController extends Controller
{
    public function index() {
      $data['packages'] = Package::where('status', 1)->latest()->get();
      return view('vendor.subscription.index', $data);
    }

    public function validitycheck() {
      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
      if ($vendor->products != 0) {
        $today = new \Carbon\Carbon(Carbon::now());
        $existingVal = new \Carbon\Carbon($vendor->expired_date);
        if ($today->gt($existingVal)) {
          $vendor->products = 0;
          $vendor->expired_date = NULL;
          $vendor->save();
          send_email( $vendor->email, $vendor->name, "Subscription Package Expired!", "Your subscription package is expired. Please buy new subscription package.");
        }
      }
      return $vendor;
    }

    public function buy(Request $request) {
      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
      $pack = Package::find($request->packid);

      // if employee balance is short than package price.
      if ($vendor->balance < $pack->price) {
        return "b_short";
      }

      $today = new \Carbon\Carbon(Carbon::now());
      $newVal = $today->addDays($pack->validity);

      if (empty($vendor->expired_date)) {
        $vendor->expired_date = $newVal;
      } else {
        $existingVal = new \Carbon\Carbon($vendor->expired_date);
        if ($newVal->gt($existingVal)) {
          $vendor->expired_date = $newVal;
        }
      }
      $vendor->products = $vendor->products + $pack->products;

      // cut employee balance
      $vendor->balance = $vendor->balance - $pack->price;
      $vendor->save();

      $tr = new Transaction;
      $tr->vendor_id = $vendor->id;
      $tr->details = "Bought " . $pack->title . " package";
      $tr->amount = $pack->price;
      $tr->trx_id = str_random(16);
      $tr->after_balance = $vendor->balance;
      $tr->save();

      Session::flash('success', 'You have bought the package!');

      return "success";
    }
}
