<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;
use Image;

class ContactController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.contact.index');
    }

    public function update(Request $request) {
      $messages = [
        'con_title.required' => 'Title is required',
        'con_phone.required' => 'Phone is required',
        'con_email.required' => 'Email is required',
        'con_address.required' => 'Adress is required',
      ];

      $validatedData = $request->validate([
        'con_address' => 'required',
        'con_phone' => 'required',
        'con_email' => 'required',
        'work_hours' => 'required',
      ], $messages);

      $gs = GS::first();
      $gs->con_address = $request->con_address;
      $gs->con_phone = $request->con_phone;
      $gs->con_email = $request->con_email;
      $gs->work_hours = $request->work_hours;
      $gs->save();

      Session::flash('success', 'Contact updated successfully!');
      return redirect()->back();
    }
}
