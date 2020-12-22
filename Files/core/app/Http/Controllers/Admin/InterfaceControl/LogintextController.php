<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;

class LogintextController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.logintext.index');
    }

    public function update(Request $request) {
      $request->validate([
        'user_login_text' => 'required',
        'vendor_login_text' => 'required',
      ]);

      $gs = GS::first();
      $in = $request->only('user_login_text', 'vendor_login_text');
      $gs->fill($in)->save();

      Session::flash('success', 'Text updated successfully');
      return back();
    }
}
