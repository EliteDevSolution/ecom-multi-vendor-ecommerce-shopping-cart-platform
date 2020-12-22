<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PasswordReset as PS;
use DB;
use Auth;


class ForgotPasswordController extends Controller
{
    public function showEmailForm() {
      return view('user.ForgotPassword.showEmailForm');
    }

    public function sendResetPassMail(Request $request)
    {

          $this->validate($request,[
                  'resetEmail' => 'required',
              ]);
          $user = User::where('email', $request->resetEmail)->first();
          if ($user == null)
          {
              return back()->with('email_not_available', 'Email Not Available');
          }
          else
          {
              $to =$user->email;
              $name = $user->name;
              $subject = 'Password Reset';
              $code = str_random(30);
              $message = 'Use This Link to Reset Password: '.url('/').'/'.'reset/'.$code;

              DB::table('password_resets')->insert(
                  ['email' => $to, 'token' => $code, 'status' => 0, 'created_at' => date("Y-m-d h:i:s")]
              );

              send_email($to, $name, $subject, $message);

              return back()->with('message', 'Password Reset Email Sent Succesfully');

          }

     }

     public function resetPasswordForm($code) {
         $ps = PS::where('token', $code)->first();

         if ($ps == null) {
             return redirect()->route('user.showEmailForm', $data);
         } else {
             if ($ps->status == 0) {
                 $user = User::where('email', $ps->email)->first();
                 $data['email'] = $user->email;
                 $data['code'] = $code;
                 return view('user.ForgotPassword.resetPassForm', $data);
             } else {
                 return redirect()->route('user.showEmailForm', $data);
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

         $user = User::where('email', $request->email)->first();
         $user->password = bcrypt($request->password);
         $user->save();
         $ps = PS::where('token', $request->code)->first();
         $ps->status = 1;
         $ps->save();

         $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
             // Authentication passed...
             return redirect()->route('user.home');
         }
     }
}
