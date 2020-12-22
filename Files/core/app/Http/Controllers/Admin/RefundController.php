<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Refund;
use App\Orderedproduct;
use App\Product;
use App\GeneralSetting as GS;
use Session;

class RefundController extends Controller
{
    public function all() {
      $data['refunds'] = Refund::orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function rejected() {
      $data['refunds'] = Refund::where('status', -1)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function accepted() {
      $data['refunds'] = Refund::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function pending() {
      $data['refunds'] = Refund::where('status', 0)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.refunds.index', $data);
    }

    public function accept(Request $request) {
      $gs = GS::first();

      $refund = Refund::find($request->rid);
      $refund->status = 1;
      $refund->save();

      $op = Orderedproduct::find($refund->orderedproduct_id);
      $op->refunded = 1;
      $op->save();

      $product = Product::find($op->product_id);
      $product->sales = $product->sales - $op->quantity;
      $product->quantity = $product->quantity + $op->quantity;
      $product->save();

      // snding mail to user
      send_email($refund->orderedproduct->user->email, $refund->orderedproduct->user->first_name, 'Refund request accepted', "Your refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug . "/" . $refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been accepted. You will get ". $refund->orderedproduct->product_total ." " . $gs->base_curr_text . ". We will contact with you later.");
      // snding mail to vendor
      send_email($refund->orderedproduct->vendor->email, $refund->orderedproduct->vendor->shop_name, 'Order refunded', "Refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug."/".$refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been accepted. You will have to return back ". $refund->orderedproduct->product_total ." " . $gs->base_curr_text . " to us. We will contact with you later.");

      Session::flash('success', 'Refund request accepted!');

      return "success";
    }

    public function reject(Request $request) {
      $gs = GS::first();

      $refund = Refund::find($request->rid);
      $refund->status = -1;
      $refund->save();

      // snding mail to user
      send_email($refund->orderedproduct->user->email, $refund->orderedproduct->user->first_name, 'Refund request rejected', "Your refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug. '/' .$refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been rejected.");
      // snding mail to vendor
      send_email($refund->orderedproduct->vendor->email, $refund->orderedproduct->vendor->shop_name, 'Order refunded', "Refund request for <a href='".url('/')."/product/".$refund->orderedproduct->product->slug . "/" . $refund->orderedproduct->product->id."'>" .$refund->orderedproduct->product->title. "</a> has been rejected.");

      Session::flash('success', 'Refund request rejected!');

      return "success";
    }
}
