<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;

class FooterController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.footer.index');
    }

    public function update(Request $request) {

      $validatedData = $request->validate([
        'footer_text' => 'required',
      ]);

      $gs = GS::first();
      $gs->footer = $request->footer_text;
      $gs->save();

      Session::flash('success', 'Footer text updated successfully!');
      return redirect()->back();
    }
}
