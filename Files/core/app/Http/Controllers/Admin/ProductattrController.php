<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductAttribute;
use App\Option;
use Session;

class ProductattrController extends Controller
{
    public function index() {
      $data['pas'] = ProductAttribute::orderBy('id', 'desc')->paginate(10);
      return view('admin.product_attribute.index', $data);
    }


    public function store(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $pa = new ProductAttribute;
      $pa->name = $request->name;
      $pa->attrname = str_slug($request->name, '_');
      $pa->status = $request->status;
      $pa->save();

      Session::flash('success', 'New product attribute added!');
      return redirect()->back();
    }


    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $pa = ProductAttribute::find($request->paId);
      $pa->name = $request->name;
      $pa->attrname = str_slug($request->name, '_');
      $pa->status = $request->status;
      $pa->save();

      Session::flash('success', 'Product attribute updated successfully');
      return redirect()->back();
    }
}
