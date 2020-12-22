<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class EmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {
       if(Auth::check() && Auth::user()->email_verified == 0) {

         // sending verification code in email...
         if (Auth::user()->email_sent == 0) {
           $to = Auth::user()->email;
           $name = Auth::user()->name;
           $subject = "Email verification code";
           $message = "Your verification code is: " . Auth::user()->email_ver_code;
           send_email( $to, $name, $subject, $message);

           // making the 'email_sent' 1 after sending mail...
           $seek = User::find(Auth::user()->id);
           $seek->email_sent = 1;
           $seek->save();
         }

         return redirect()->route('user.showEmailVerForm');
       }
       return $next($request);
     }
}
