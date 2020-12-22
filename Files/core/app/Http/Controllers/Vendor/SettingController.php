<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use App\Vendor;
use Auth;
use Image;
use Session;

class SettingController extends Controller
{
    public function settings() {
      return view('vendor.settings');
    }

    public function update(Request $request) {
      // return $request;
      $vendor = Vendor::find(Auth::guard('vendor')->user()->id);

      $request->validate([
        'shop_name' => [
          'required',
          Rule::unique('vendors')->ignore($vendor->id),
        ],
        'phone' => 'required',
        'address' => 'required',
        'zip_code' => 'required',
      ]);

      $in = Input::except('_token', 'logo');
      if($request->hasFile('logo')) {
        $imagePath = 'assets/user/img/shop-logo/' . $vendor->logo;
        @unlink($imagePath);
        $image = $request->file('logo');
        $fileName = time() . '.jpg';
        $location = './assets/user/img/shop-logo/' . $fileName;
        $background = Image::canvas(250, 250);
        $resizedImage = Image::make($image)->resize(250, 250, function ($c) {
            $c->aspectRatio();
        });
        // insert resized image centered into background
        $background->insert($resizedImage, 'center');
        // save or do whatever you like
        $background->save($location);
        $in['logo'] = $fileName;

      }
      $vendor->fill($in)->save();

      Session::flash('success', 'Updated successfully!');

      return redirect()->back();
    }
}
