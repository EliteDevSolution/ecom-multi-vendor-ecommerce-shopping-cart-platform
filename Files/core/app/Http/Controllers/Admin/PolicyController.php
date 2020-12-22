<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;

class PolicyController extends Controller
{
    public function refund() {
      return view('admin.refund_policy');
    }

    public function refundupdate(Request $request) {
      $request->validate([
        'refund_policy' => 'required',
      ]);

      $gs = GS::first();
      $in = $request->only('refund_policy');
      $gs->fill($in)->save();

      Session::flash('success', 'Policy updated successfully');
      return back();
    }

    public function replacement() {
      return view('admin.replacement_policy');
    }

    public function replacementupdate(Request $request) {
      $request->validate([
        'replacement_policy' => 'required',
      ]);

      $gs = GS::first();
      $in = $request->only('replacement_policy');
      $gs->fill($in)->save();

      Session::flash('success', 'Policy updated successfully');
      return back();
    }
}
