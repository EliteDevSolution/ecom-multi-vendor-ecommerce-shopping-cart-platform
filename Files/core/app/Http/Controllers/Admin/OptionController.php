<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
use App\ProductAttribute;
use Session;

class OptionController extends Controller
{
    public function index($id) {
      $data['pa'] = ProductAttribute::find($id);
      $data['options'] = Option::where('product_attribute_id', $id)->orderBy('id', 'DESC')->paginate(10);
      return view('admin.options.index', $data);
    }

    public function store(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $option = new Option;
      $option->product_attribute_id = $request->product_attribute_id;
      $option->name = $request->name;
      $option->status = $request->status;
      $option->save();

      Session::flash('success', 'New option added!');
      return redirect()->back();
    }

    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $option = Option::find($request->option_id);
      $option->name = $request->name;
      $option->status = $request->status;
      $option->save();

      Session::flash('success', 'Option updated successfully');
      return redirect()->back();
    }
}
