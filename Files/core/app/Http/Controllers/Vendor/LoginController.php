<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use Auth;
use Validator;
use Session;

class LoginController extends Controller
{
    public function login() {
      return view('vendor.login');
    }

    public function authenticate(Request $request) {
        if (Auth::check()) {
          Session::flash('alert', 'You are already logged in as an user');
          return back();
        }

        $vendor = Vendor::where('email', $request->email)->first();
        if (!empty($vendor) && ($vendor->approved == 0 || $vendor->approved == -1)) {
          return back()->with('missmatch', 'Username/Password didn\'t match!');
        }

        $validatedRequest = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('vendor')->attempt([
          'email' => $request->email,
          'password' => $request->password,
        ])) {
            return redirect()->route('vendor.dashboard');
        } else {
            return back()->with('missmatch', 'Username/Password didn\'t match!');
        }
    }

    public function logout($id = null) {
      Auth::guard('vendor')->logout();
      if ($id) {
          $vendor = Vendor::find($id);
          if ($vendor->status == 'blocked') {
              Session::flash('alert', 'Your account has been banned');
          }
      }
      session()->flash('message', 'Just Logged Out!');
      return redirect()->route('user.home');
    }
}
