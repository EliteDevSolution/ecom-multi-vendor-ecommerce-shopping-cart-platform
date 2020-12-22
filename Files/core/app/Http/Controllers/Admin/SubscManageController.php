<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Subscriber;
use Session;
use App\Jobs\SendWelcomeEmail;

class SubscManageController extends Controller
{
    public function subscribers() {
      $data['subscribers'] = Subscriber::all();
      return view('admin.subscribers.index', $data);
    }

    public function mailtosubsc(Request $request) {
      $validatedRequest = $request->validate([
        'subject' => 'required',
        'message' => 'required'
      ]);
      $subject = $request->subject;
         $message = $request->message;

         $a = SendWelcomeEmail::dispatch($subject, $message)->delay(Carbon::now()->addSeconds(5));

      Session::flash('success', 'Mail sent to all subscribers.');
      return redirect()->back();
    }
}
