<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Provider;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Cart;
use App\PlacePayment;
use Validator;
use Session;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    protected $field = ['first_name', 'last_name', 'email', 'gender', 'birthday', 'location'];

    public function login() {
      return view('user.login');
    }

    public function authenticate(Request $request) {
        if (Auth::guard('vendor')->check()) {
          Session::flash('alert', 'You are already logged in as a vendor');
          return back();
        }

        $validatedRequest = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();
        if (Auth::attempt([
          'username' => $request->username,
          'password' => $request->password,
        ])) {
            $userId = Auth::user()->id;

            // if the guest Cart is containg any product...
            if (Cart::where('cart_id', session()->get('browserid'))->count() > 0) {
              foreach (Cart::where('cart_id', session()->get('browserid'))->get() as $guestcart) {
                // if the guest cart is containing a product which is already in the logged in user's Cart...
                if (Cart::where('cart_id', $userId)->where('product_id', $guestcart->product_id)->where('attributes', '[]')->count() > 0) {
                  // increase the product quantity in logged in user's cart...
                  $authcart = Cart::where('cart_id', $userId)->where('product_id', $guestcart->product_id)->first();
                  $authcart->quantity = $authcart->quantity + $guestcart->quantity;
                  $authcart->save();
                } else {
                  // replacing the the guest cart's cart_id with logged in users ID...
                  $guestcart->cart_id = $userId;
                  $guestcart->save();
                }
              }
              // clear browser cart
              Cart::where('cart_id', session()->get('browserid'))->delete();
            }

            $guestpp = PlacePayment::where('cart_id', session()->get('browserid'));
            $authpp = PlacePayment::where('cart_id', $userId);
            // if there is any row in `place_payments` for `browserid`...
            if ($guestpp->count() > 0) {
              // if there is no row in `place_payments` for this logged in user then just store place, payment for that loggedin user...
              if ($authpp->count() == 0) {
                $authpprow = new PlacePayment;
                $authpprow->cart_id = $userId;
                $authpprow->place = $guestpp->first()->place;
                $authpprow->payment = $guestpp->first()->payment;
                $authpprow->save();
              }
              // clear browser cart
              $guestpp->delete();
            }
            return redirect()->intended(route('user.home'));
        } else {
            return back()->with('missmatch', 'Username/Password didn\'t match!');
        }
    }

    public function logout($id = null) {
      Auth::logout();
      if ($id) {
          $user = User::find($id);
          if ($user->status == 'blocked') {
              Session::flash('alert', 'Your account has been banned');
          }
      }
      session()->flash('message', 'Just Logged Out!');
      return redirect()->route('user.home');
    }


    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
     public function redirectToProvider($provider)

       {

           $provider = Provider::where('provider', $provider)->first();

           Config::set('services.' . $provider->provider, [

               'client_id' => $provider->client_id,

               'client_secret' => $provider->client_secret,

               'redirect' => url('/').'/auth/' . $provider->provider . '/callback',

           ]);

           if ($provider->provider == 'facebook') {
               return Socialite::driver($provider->provider)->fields($this->field)->scopes([
                   'email', 'user_birthday'
               ])->redirect();
           } elseif('twitter') {
               return Socialite::with('Twitter')->redirect();
           } else {
               return Socialite::driver($provider->provider)->scopes([
                   'https://www.googleapis.com/auth/userinfo.email',
                   'profile',
                   'https://www.googleapis.com/auth/plus.login',
                   'https://www.googleapis.com/auth/plus.me'
               ])->redirect();
           }

       }

       /**
        * Obtain the user information from provider.  Check if the user already exists in our
        * database by looking up their provider_id in the database.
        * If the user exists, log them in. Otherwise, create a new user then log them in. After that
        * redirect them to the authenticated users homepage.
        *
        * @return Response
        */
       public function handleProviderCallback($provider)

       {

           try {

               $provider = Provider::where('provider', $provider)->first();

               Config::set('services.' . $provider->provider, [

                   'client_id' => $provider->client_id,

                   'client_secret' => $provider->client_secret,

                   'redirect' => url('/').'/auth/' . $provider->provider . '/callback',

               ]);


               if ($provider->provider == 'google') {
                   $userSocial = Socialite::driver($provider->provider)->stateless()->user();
               } elseif ($provider->provider == 'twitter') {
                   $userSocial = Socialite::driver('Twitter')->user();
               } else {
                   $userSocial = Socialite::driver($provider->provider)->fields($this->field)->user();
               }


               $authUser = $this->findOrCreateUser($userSocial, $provider->provider);
               Auth::login($authUser, true);

               // guest cart to auth cart product transfer...
               $userId = Auth::user()->id;

               // if the guest Cart is containg any product...
               if (Cart::where('cart_id', session()->get('browserid'))->count() > 0) {
                 foreach (Cart::where('cart_id', session()->get('browserid'))->get() as $guestcart) {
                   // if the guest cart is containing a product which is already in the logged in user's Cart...
                   if (Cart::where('cart_id', $userId)->where('product_id', $guestcart->product_id)->where('attributes', '[]')->count() > 0) {
                     // increase the product quantity in logged in user's cart...
                     $authcart = Cart::where('cart_id', $userId)->where('product_id', $guestcart->product_id)->first();
                     $authcart->quantity = $authcart->quantity + $guestcart->quantity;
                     $authcart->save();
                   } else {
                     // replacing the the guest cart's cart_id with logged in users ID...
                     $guestcart->cart_id = $userId;
                     $guestcart->save();
                   }
                 }
                 // clear browser cart
                 Cart::where('cart_id', session()->get('browserid'))->delete();
               }

               $guestpp = PlacePayment::where('cart_id', session()->get('browserid'));
               $authpp = PlacePayment::where('cart_id', $userId);
               // if there is any row in `place_payments` for `browserid`...
               if ($guestpp->count() > 0) {
                 // if there is no row in `place_payments` for this logged in user then just store place, payment for that loggedin user...
                 if ($authpp->count() == 0) {
                   $authpprow = new PlacePayment;
                   $authpprow->cart_id = $userId;
                   $authpprow->place = $guestpp->first()->place;
                   $authpprow->payment = $guestpp->first()->payment;
                   $authpprow->save();
                 }
                 // clear browser cart
                 $guestpp->delete();
               }


               return redirect()->intended(route('user.home'));


           } catch (\Exception $e) {

               return redirect('login')->withErrors('Error! Failed To Connect.' . $e->getMessage());
           }

       }

       /**
        * If a user has registered before using social auth, return the user
        * else, create a new user object.
        * @param  $user Socialite user object
        * @param $provider Social auth provider
        * @return  User
        */
       public function findOrCreateUser($user, $provider)

       {


           $authUser = User::where(['provider' => $provider, 'provider_id' => $user->id])->first();

           if ($authUser) {

               return $authUser;

           }

           $authUser = new User;
           $authUser->first_name = ($user->getName() != null)?$user->getName():$provider . '_' . $user->id;
           $authUser->username = $provider . '_' . $user->id;
           $authUser->email = ($user->getEmail() != null)?$user->getEmail():$user->id . '@' . $provider;
           $authUser->email_verified = 1;
           $authUser->provider = $provider;
           $authUser->provider_id = $user->id;
           $authUser->save();

           return $authUser;

       }
}
