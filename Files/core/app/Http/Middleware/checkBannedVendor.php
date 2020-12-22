<?php

namespace App\Http\Middleware;

use Closure;
use App\Vendor;
use Auth;
use Session;

class checkBannedVendor
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
         if (Auth::guard('vendor')->check()) {
             if (Auth::guard('vendor')->user()->status == 'blocked') {
                 return redirect()->route('vendor.logout', Auth::guard('vendor')->user()->id);
             }
         }
         return $next($request);
     }
}
