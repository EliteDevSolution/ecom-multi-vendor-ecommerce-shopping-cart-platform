<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\VendorPasswordReset as PS;
use DB;
use Auth;


class ForgotPasswordController extends Controller
{
    public function showEmailForm() {
      return view('vendor.ForgotPassword.showEmailForm');
    }

    public function sendResetPassMail(Request $request)
    {

          $this->validate($request,[
                  'resetEmail' => 'required',
              ]);
          $vendor = Vendor::where('email', $request->resetEmail)->where('approved', 1)->first();
          if ($vendor == null)
          {
              return back()->with('email_not_available', 'Email Not Available');
          }
          else
          {
              $to =$vendor->email;
              $name = $vendor->name;
              $subject = 'Password Reset';
              $code = str_random(30);
              $message = 'Use This Link to Reset Password: '.url('/').'/vendor/reset/'.$code;

              DB::table('vendor_password_resets')->insert(
                  ['email' => $to, 'token' => $code, 'status' => 0, 'created_at' => date("Y-m-d h:i:s")]
              );

              send_email($to, $name, $subject, $message);

              return back()->with('message', 'Password Reset Email Sent Succesfully');

          }

     }

     public function resetPasswordForm($code) {
         $ps = PS::where('token', $code)->first();

         if ($ps == null) {
             return redirect()->route('vendor.showEmailForm', $data);
         } else {
             if ($ps->status == 0) {
                 $vendor = Vendor::where('email', $ps->email)->first();
                 $data['email'] = $vendor->email;
                 $data['code'] = $code;
                 return view('vendor.ForgotPassword.resetPassForm', $data);
             } else {
                 return redirect()->route('vendor.showEmailForm', $data);
             }
         }
     }

     public function resetPassword(Request $request) {
         $messages = [
             'password_confirmation.confirmed' => 'Password doesnot match'
         ];

         $validatedData = $request->validate([
             'password' => 'required|confirmed',
         ], $messages);

         $vendor = Vendor::where('email', $request->email)->first();
         $vendor->password = bcrypt($request->password);
         $vendor->save();
         $ps = PS::where('token', $request->code)->first();
         $ps->status = 1;
         $ps->save();

         $credentials = $request->only('email', 'password');
         if (Auth::guard('vendor')->attempt($credentials)) {
             // Authentication passed...
             return redirect()->route('vendor.dashboard');
         }
     }
}
