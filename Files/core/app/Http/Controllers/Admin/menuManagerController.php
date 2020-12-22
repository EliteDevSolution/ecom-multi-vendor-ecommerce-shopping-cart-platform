<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Menu;
use Session;

class menuManagerController extends Controller
{
    public function index() {
      $data['menus'] = Menu::latest()->get();
      return view('admin.menuManager.index', $data);
    }

    public function add() {
      return view('admin.menuManager.add');
    }

    public function store(Request $request) {
      $slug = str_slug($request->name, '-');

      $validatedRequest = $request->validate([
        'name' => 'required',
        'title' => 'required',
        'body' => 'required'
      ]);

      $menu = new Menu;
      $menu->name = $request->name;
      $menu->title = $request->title;
      $menu->slug = $slug;
      $menu->body = $request->body;
      $menu->save();

      Session::flash('success', 'Menu added successfully!');
      return redirect()->back();
    }

    public function edit($menuID) {
      $data['menu'] = Menu::find($menuID);
      return view('admin.menuManager.edit', $data);
    }

    public function update(Request $request, $menuID) {
      $slug = str_slug($request->name, '-');

      $validatedRequest = $request->validate([
        'name' => 'required',
        'title' => 'required',
        'body' => 'required'
      ]);

      $menu = Menu::find($menuID);
      $menu->name = $request->name;
      $menu->title = $request->title;
      $menu->slug = $slug;
      $menu->body = $request->body;
      $menu->save();

      Session::flash('success', 'Menu updated successfully!');
      return redirect()->back();
    }

    public function delete($menuID) {
      $menu = Menu::find($menuID);
      $menu->delete();
      Session::flash('success', 'Menu deleted successfully!');
      return redirect()->back();
    }
}
