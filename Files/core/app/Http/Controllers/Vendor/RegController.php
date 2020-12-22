<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\GeneralSetting as GS;
use Auth;
use Session;

class RegController extends Controller
{
    public function showRegForm() {
      return view('vendor.register');
    }

    public function register(Request $request) {
        // return $request->all();

        $gs = GS::first();
        if ($gs->registration == 0) {
          Session::flash('alert', 'Registration is closed by Admin');
          return back();
        }

        $validatedRequest = $request->validate([
            'shop_name' => 'required|unique:vendors',
            'email' => 'required|email|max:255|unique:vendors',
            'phone' => 'required',
            'password' => 'required|confirmed'
        ]);


        $vendor = new Vendor;
        $vendor->shop_name = $request->shop_name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);

        $vendor->save();

        return back()->with('message', 'Your informations will be reviewed by Admin. We will let you know about the update (after review) through Phone\Email once it\'s been checked!');
    }
}
