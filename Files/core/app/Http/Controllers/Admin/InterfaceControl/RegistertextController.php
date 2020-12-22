<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;

class RegistertextController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.regtext.index');
    }

    public function update(Request $request) {
      $request->validate([
        'user_register_text' => 'required',
        'vendor_register_text' => 'required',
      ]);

      $gs = GS::first();
      $in = $request->only('user_register_text', 'vendor_register_text');
      $gs->fill($in)->save();

      Session::flash('success', 'Text updated successfully');
      return back();
    }
}
