<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\GeneralSetting as GS;
use App\Partner;
use App\Http\Controllers\Controller;
use Image;
use Session;

class PartnerController extends Controller
{
    public function index() {
      $data['partners'] = Partner::all();
      return view('admin.interfaceControl.partner.index', $data);
    }

    public function store(Request $request) {

      $validatedData = $request->validate([
          'partner' => 'required|mimes:jpeg,jpg,png',
          'url' => 'required',
      ]);

      $partner = new Partner;
      $partner->url = $request->url;
      if($request->hasFile('partner')) {
        $image = $request->file('partner');
        $fileName = time() . '.jpg';
        $location = './assets/user/interfaceControl/partners/' . $fileName;
        Image::make($image)->save($location);
        $partner->image = $fileName;
      }
      $partner->save();
      Session::flash('success', 'Added successfully!');
      return redirect()->back();
    }

    public function delete(Request $request) {
      $partner = Partner::find($request->partnerID);
      $imagePath = './assets/user/interfaceControl/partners/' . $partner->image;
      @unlink($imagePath);
      $partner->delete();

      Session::flash('success', 'Deleted successfully!');
      return redirect()->back();
    }
}
