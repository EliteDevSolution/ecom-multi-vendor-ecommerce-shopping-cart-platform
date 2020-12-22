<?php

namespace App\Http\Controllers\Admin\InterfaceControl;

use Illuminate\Http\Request;
use App\GeneralSetting as GS;
use App\Slider as Slider;
use App\Http\Controllers\Controller;
use Image;
use Session;

class SliderController extends Controller
{

    public function index() {
      $sliders = Slider::all();
      return view('admin.interfaceControl.slider.index', ['sliders' => $sliders]);
    }

    public function store(Request $request) {
      $messages = [
        'btxt.required' => 'bold text field is required',
        'stxt.required' => 'small text field is required',
      ];
      $validatedData = $request->validate([
          'slider' => 'required|mimes:jpeg,jpg,png',
          'title' => 'required',
          'url' => 'required',
          'btxt' => 'required',
          'stxt' => 'required'
      ], $messages);

      $slider = new Slider;
      $slider->title = $request->title;
      $slider->url = $request->url;
      $slider->small_text = $request->stxt;
      $slider->bold_text = $request->btxt;
      if($request->hasFile('slider')) {
        $image = $request->file('slider');
        $fileName = time() . '.jpg';
        $location = './assets/user/interfaceControl/sliders/' . $fileName;
        Image::make($image)->resize(1360, 550)->save($location);
        $slider->image = $fileName;
      }
      $slider->save();
      Session::flash('success', 'Slider image added successfully!');
      return redirect()->back();
    }

    public function delete(Request $request) {
      $slider = Slider::find($request->sliderID);
      $imagePath = './assets/user/interfaceControl/sliders/' . $slider->image;
      if(file_exists($imagePath)) {
        unlink($imagePath);
      }
      $slider->delete();

      Session::flash('success', 'Slider image deleted successfully!');
      return redirect()->back();
    }
}
