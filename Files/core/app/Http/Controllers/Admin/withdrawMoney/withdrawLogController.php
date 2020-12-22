<?php

namespace App\Http\Controllers\Admin\withdrawMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Withdraw as Withdraw;
use App\Vendor;
use Validator;
use App\Transaction;

class withdrawLogController extends Controller
{
    public function withdrawLog() {
      $withdraws = Withdraw::latest()->paginate(15);
      $data['withdraws'] = $withdraws;
      return view('admin.withdrawMoney.withdrawLog.withdrawLog', $data);
    }

    public function show($wID) {
      $withdraw = Withdraw::find($wID);
      $data['withdraw'] = $withdraw;
      return view('admin.withdrawMoney.withdrawLog.show', $data);
    }

    public function storeMessage(Request $request) {
      $gs = GS::first();

      $rules = [
        'message' => 'required'
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        $validator->getMessageBag()->add('error', 'true');
        return response()->json($validator->errors());
      }

      $withdraw = Withdraw::find($request->wID);
      $withdraw->status = $request->status;
      $withdraw->message = $request->message;
      $withdraw->save();

      if ($request->status == "refunded") {
          $vendor = Vendor::find($withdraw->vendor->id);
          $vendor->balance = $vendor->balance + ($withdraw->amount+$withdraw->charge);
          $vendor->save();
      }

      if ($request->status == "processed") {
          $vendor = Vendor::find($withdraw->vendor->id);

          $tr = new Transaction;
          $tr->vendor_id = $vendor->id;
          $tr->details = "Withdraw via " . $withdraw->withdrawMethod->name;
          $tr->amount = $withdraw->amount;
          $tr->trx_id = str_random(16);
          $tr->after_balance = $vendor->balance;
          $tr->save();
      }

      // if email notification is on then send mail...
      if ($gs->email_notification == 1) {
          $to = $withdraw->vendor->email;
          $name = $withdraw->vendor->shop_name;

          if ($request->status == "processed") {
              $subject = "Withdraw Request Processed";
              $message = $withdraw->message;
              send_email( $to, $name, $subject, $message);
          }

          if ($request->status == "refunded") {
              $subject = "Withdraw Request Refunded";
              $message = $withdraw->message;
              send_email( $to, $name, $subject, $message);
          }
      }


      return "success";
    }
}
