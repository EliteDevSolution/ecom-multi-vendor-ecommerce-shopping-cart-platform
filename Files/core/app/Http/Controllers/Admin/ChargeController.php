<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;
use Validator;

class ChargeController extends Controller
{
    public function index() {
      return view('admin.charge.index');
    }

    public function shippingupdate(Request $request) {
        $validator = Validator::make($request->all(), [
          'in_min' => 'required',
          'in_max' => 'required',
          'am_min' => 'required',
          'am_max' => 'required',
          'aw_min' => 'required',
          'aw_max' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
                        // ->withInput();
        }
        
        
      $gs = GS::first();
      
      
        $gs->in_min = $request->in_min;
        $gs->in_max = $request->in_max;
        $gs->am_min = $request->am_min;
        $gs->am_max = $request->am_max;
        $gs->aw_min = $request->aw_min;
        $gs->aw_max = $request->aw_max;

      $gs->in_cash_on_delivery = $request->in_cash_on_delivery !='' ? $request->in_cash_on_delivery : 0.00;
      $gs->in_advanced = $request->in_advanced != '' ? $request->in_advanced : 0.00;
      $gs->around_cash_on_delivery = $request->around_cash_on_delivery !='' ? $request->around_cash_on_delivery : 0.00;
      $gs->around_advanced = $request->around_advanced !='' ? $request->around_advanced : 0.00;
      $gs->world_cash_on_delivery = $request->world_cash_on_delivery!=''? $request->world_cash_on_delivery : 0.00;
      $gs->world_advanced = $request->world_advanced !='' ? $request->world_advanced : 0.00;
      $gs->tax = $request->tax !='' ? $request->tax : 0.00;
      $gs->save();

      Session::flash('success', 'Updated successfully!');

      return back();
    }


}
