<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Provider;
use Session;

class FacebookController extends Controller
{
    public function index() {
      $data['provider'] = Provider::find(1);
      return view('admin.facebook', $data);
    }

    public function update(Request $request) {

      $validatedData = $request->validate([
        'app_id' => 'required',
        'app_secret' => 'required',
      ]);

      $provider = Provider::find(1);
      $provider->status = $request->status;
      $provider->client_id = $request->app_id;
      $provider->client_secret = $request->app_secret;
      $provider->save();

      Session::flash('success', 'Updated successfully!');
      return redirect()->back();
    }
}
