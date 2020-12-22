<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Coupon;
use Session;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index() {
      $data['coupons'] = Coupon::orderBy('id', 'DESC')->get();
      return view('admin.coupon-lists', $data);
    }

    public function create() {
      return view('admin.coupon');
    }

    public function edit($id) {
      $data['coupon'] = Coupon::find($id);
      return view('admin.coupon-edit', $data);
    }

    public function store(Request $request) {
      $request->validate([
        'coupon_code' => 'required|unique:coupons',
        'coupon_amount' => 'required',
        'minimum_amount' => 'required_if:coupon_type,fixed',
        // 'minimum_amount' => [
        //   function ($attribute, $value, $fail) use ($request) {
        //       if ($request->type == 'fixed') {
        //           $fail('Minimum amount is required');
        //       }
        //   },
        // ],
        'valid_till' => 'required',
        'type_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->has('coupon_type')) {
                  $fail('Type is required');
              }
          },
        ]
      ]);

      // $gs = GS::first();
      // $in = $request->except('_token', 'type_helper', 'valid_till', 'minimum_amount');
      // $valid_till = new \Carbon\Carbon($request->valid_till);
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // if ($request->coupon_type == 'fixed') {
      //   $in['coupon_min_amount'] = $request->minimum_amount;
      // }
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // $gs->fill($in)->save();

      $coupon = new Coupon;
      $coupon->coupon_code = $request->coupon_code;
      $coupon->coupon_type = $request->coupon_type;
      $coupon->coupon_amount = $request->coupon_amount;
      $coupon->coupon_min_amount = $request->minimum_amount;
      $coupon->valid_till = $request->valid_till;
      $coupon->save();

      Session::flash('success', 'Coupon added successfully!');
      return back();
    }

    public function update(Request $request) {
      $coupon = Coupon::find($request->coupon_id);

      $request->validate([
        'coupon_code' => [
            'required',
            Rule::unique('coupons')->ignore($coupon->id),
        ],
        'coupon_amount' => 'required',
        'minimum_amount' => 'required_if:coupon_type,fixed',
        'valid_till' => 'required',
        'type_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->has('coupon_type')) {
                  $fail('Type is required');
              }
          },
        ]
      ]);

      // $gs = GS::first();
      // $in = $request->except('_token', 'type_helper', 'valid_till', 'minimum_amount');
      // $valid_till = new \Carbon\Carbon($request->valid_till);
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // if ($request->coupon_type == 'fixed') {
      //   $in['coupon_min_amount'] = $request->minimum_amount;
      // }
      // $in['valid_till'] = $valid_till->format('Y-m-d');
      // $gs->fill($in)->save();


      $coupon->coupon_code = $request->coupon_code;
      $coupon->coupon_type = $request->coupon_type;
      $coupon->coupon_amount = $request->coupon_amount;
      $coupon->coupon_min_amount = $request->minimum_amount;
      $coupon->valid_till = $request->valid_till;
      $coupon->save();

      Session::flash('success', 'Coupon updated successfully!');
      return back();
    }

    public function delete(Request $request) {
      $coupon = Coupon::find($request->coupon_id);
      $coupon->delete();

      Session::flash('success', 'Coupon deleted successfully!');
      return back();
    }
}
