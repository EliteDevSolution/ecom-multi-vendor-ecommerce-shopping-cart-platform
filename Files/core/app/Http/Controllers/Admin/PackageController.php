<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package;
use Session;

class PackageController extends Controller
{
    public function index() {
      $data['packages'] = Package::latest()->get();
      return view('admin.packages.index', $data);
    }

    public function store(Request $request) {
      $messages = [
        's_desc.required' => 'The short description is required',
      ];
      $validatedData = $request->validate([
          'title' => 'required',
          's_desc' => 'required|max:190',
          'price' => 'required',
          'products' => 'required',
          'validity' => 'required'
      ], $messages);

      $package = new Package;
      $package->title = $request->title;
      $package->s_desc = $request->s_desc;
      $package->price = $request->price;
      $package->products = $request->products;
      $package->validity = $request->validity;
      $package->status = $request->status;

      $package->save();

      Session::flash('success', 'A new package is created');

      return redirect()->back();
    }

    public function update(Request $request) {
      $messages = [
        's_desc.required' => 'The short description is required',
      ];
      $validatedData = $request->validate([
          'title' => 'required',
          's_desc' => 'required|max:190',
          'price' => 'required',
          'products' => 'required',
          'validity' => 'required'
      ], $messages);

      $package = Package::find($request->packageID);
      $package->title = $request->title;
      $package->s_desc = $request->s_desc;
      $package->price = $request->price;
      $package->products = $request->products;
      $package->validity = $request->validity;
      $package->status = $request->status;

      $package->save();

      Session::flash('success', 'Package is updated successfully!');

      return redirect()->back();
    }
}
