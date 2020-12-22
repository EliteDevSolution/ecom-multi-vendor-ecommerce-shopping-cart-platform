<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WithdrawMethod as WM;
use App\Withdraw;
use App\Vendor;
use Auth;
use Validator;

class WithdrawMoneyController extends Controller
{
    public function withdrawMoney() {
      $wms = WM::where('deleted', 0)->get();
      $data['wms'] = $wms;

      return view('vendor.withdrawMoney.withdrawMoney', $data);
    }

    public function store(Request $request) {
      $wm = WM::find($request->wmID);
      // calculating the total charge for this withdraw method and this requested amount...


      $rules = [
        'amount' => [
          'required',
          'numeric',
          function($attribute, $value, $fail) use ($wm, $request) {
            if (is_numeric($request->amount)) {
              $charge = $wm->fixed_charge + (($wm->percentage_charge*$request->amount)/100);
              // if the amount is greater than maximum limit...
              if ($value > $wm->max_limit) {
                return $fail('Maximum amount limit is '.$wm->max_limit);
              }
              // if user balance is less than (requested amount + charge)...
              if (Auth::guard('vendor')->user()->balance < ($value + $charge)) {
                return $fail('You dont have enough balance in your account to make this withdraw request!');
              }
              // if the amount is less than minimum limit...
              if ($value < $wm->min_limit) {
                return $fail('Minimum amount limit is '.$wm->min_limit);
              }
            }
          }
        ],
        'details' => 'required'
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        $validator->getMessageBag()->add('error', 'true');
        return response()->json($validator->errors());
      }

      $charge = $wm->fixed_charge + (($wm->percentage_charge*$request->amount)/100);
      // if all validation passes then save the withdraw request in the database...
      $withdraw = new Withdraw;
      $withdraw->trx = str_random(12);
      $withdraw->vendor_id = Auth::guard('vendor')->user()->id;
      $withdraw->amount = $request->amount;
      $withdraw->withdraw_method_id = $wm->id;
      $withdraw->charge = $charge;
      $withdraw->status = 'pending';
      $withdraw->details = $request->details;
      $withdraw->save();

      // cut user balance..
      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
      $vendor->balance = $vendor->balance - ($withdraw->charge + $withdraw->amount);
      $vendor->save();

      return "success";
    }
}
