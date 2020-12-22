<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FlashInterval;
use App\Product;
use Carbon\Carbon;
use Session;

class FlashsaleController extends Controller
{
    public function times() {
      $data['intervals'] = FlashInterval::all();
      if (FlashInterval::count() > 0) {
        $data['lastid'] = FlashInterval::orderBy('id', 'DESC')->first()->id;
      } else {
        $data['lastid'] = 0;
      }
      return view('admin.flashsale.times', $data);
    }

    public function updatetimes(Request $request) {
      // return $request;
      $start = $request->start_time;
      $end = $request->end_time;
      if (empty($start)) {
        $start = [];
      }
      if (empty($end)) {
        $end = [];
      }

      FlashInterval::truncate();

      for ($i=0; $i < count($start); $i++) {
        $flashint = new FlashInterval;
        // if(substr($request->start_time[$i], 0, 2) == "00") {
        //   $flashint->start_time = str_replace("00","24",$request->start_time[$i]);
        // } else {
        //   $flashint->start_time = $request->start_time[$i];
        // }
        if(substr($request->end_time[$i], 0, 2) == "00") {
          $flashint->end_time = substr_replace($request->end_time[$i],"24",0,2);
        } else {
          $flashint->end_time = $request->end_time[$i];
        }
        $flashint->start_time = $request->start_time[$i];
        // $flashint->end_time = $request->end_time[$i];
        $flashint->save();
      }

      Session::flash('success', 'Flash time intervals updated');
      return back();
    }

    public function all(Request $request) {
      if (empty($request->term)) {
        $data['term'] = '';
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->paginate(10);
      } else {
        $data['term'] = $request->term;
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('title', 'like', '%'.$request->term.'%')->paginate(10);
      }

      return view('admin.flashsale.index', $data);
    }

    public function pending(Request $request) {
      if (empty($request->term)) {
        $data['term'] = '';
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', 0)->paginate(10);
      } else {
        $data['term'] = $request->term;
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', 0)->where('title', 'like', '%'.$request->term.'%')->paginate(10);
      }

      return view('admin.flashsale.index', $data);
    }

    public function accepted(Request $request) {
      if (empty($request->term)) {
        $data['term'] = '';
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', 1)->paginate(10);
      } else {
        $data['term'] = $request->term;
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', 1)->where('title', 'like', '%'.$request->term.'%')->paginate(10);
      }

      return view('admin.flashsale.index', $data);
    }

    public function rejected(Request $request) {
      if (empty($request->term)) {
        $data['term'] = '';
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', -1)->paginate(10);
      } else {
        $data['term'] = $request->term;
        $data['products'] = Product::orderBy('flash_request_date', 'DESC')->where('flash_sale', 1)->where('flash_status', -1)->where('title', 'like', '%'.$request->term.'%')->paginate(10);
      }

      return view('admin.flashsale.index', $data);
    }

    public function changeStatus(Request $request) {
      $product = Product::find($request->id);
      $product->flash_status = $request->status;
      $product->save();

      Session::flash('success', 'Status changed successfully');
      return "success";
    }
}
