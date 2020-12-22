<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;

class SupportController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.support.index');
    }

    public function update(Request $request) {
      $messages = [
        'support_phone.required' => 'Phone is required',
        'support_email.required' => 'Email is required',
      ];

      $validatedData = $request->validate([
        'support_phone' => 'required',
        'support_email' => 'required',
      ], $messages);

      $gs = GS::first();
      $gs->support_phone = $request->support_phone;
      $gs->support_email = $request->support_email;
      $gs->save();

      Session::flash('success', 'Support informations updated successfully!');
      return redirect()->back();
    }
}
