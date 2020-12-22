<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use Session;
use Image;

class HeroAreaController extends Controller
{
    public function index() {
      return view('admin.interfaceControl.hero.index');
    }

    public function update(Request $request) {
      $messages = [
          'sText.required' => 'Small Text field is required',
          'bText.required' => 'Bold Text field is required',
      ];
      $validatedRequest = $request->validate([
          'title' => 'required',
          'sText' => 'required',
          'bText' => 'required',
          'image' => 'mimes:jpeg,jpg,png|max:8048',
      ], $messages);
      // return $request->all();
      $gs = GS::first();
      $gs->hero_title = $request->title;
      $gs->hero_stext = $request->sText;
      $gs->hero_btext = $request->bText;
      if($request->hasFile('image')) {
        $bannerImagePath = './assets/user/interfaceControl/hero/hero.jpg';
        @unlink($bannerImagePath);
        $request->file('image')->move('assets/user/interfaceControl/hero', 'hero.jpg');
      }
      $gs->save();
      Session::flash('success', 'Updated successfully!');
      return redirect()->back();
    }
}
