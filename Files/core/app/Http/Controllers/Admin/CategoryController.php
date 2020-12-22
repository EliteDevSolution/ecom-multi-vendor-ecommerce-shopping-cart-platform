<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Session;

class CategoryController extends Controller
{
    public function index() {
      $data['cats'] = Category::latest()->paginate(10);
      return view('admin.category.index', $data);
    }

    public function store(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $cateogry = new Category;
      $cateogry->name = $request->name;
      $cateogry->save();

      Session::flash('success', 'Category added successfully');
      return redirect()->back();
    }

    public function update(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
      ]);

      $cat = Category::find($request->statusId);
      $cat->name = $request->name;
      $cat->status = $request->status;
      $cat->save();

      Session::flash('success', 'Category updated successfully');
      return redirect()->back();
    }

}
