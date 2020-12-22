<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use App\Gateway;
use App\Coupon;
use App\Product;
use App\Orderedproduct;
use App\GeneralSetting as GS;
use Carbon\Carbon;
use Auth;
use App\Cart;
use App\PlacePayment;
use Session;
use Validator;

class CheckoutController extends Controller
{
    public function index() {
      $gs = GS::first();
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      if (empty($gs->coupon_code)) {
        if (CartCoupon::where('cart_id', Auth::user()->id)->count() > 0) {
          CartCoupon::where('cart_id', Auth::user()->id)->first()->delete();
        }
      }
      $cartItems = Cart::where('cart_id', Auth::user()->id)->get();
      $data['cartItems'] = $cartItems;
      $amo = 0;
      foreach ($cartItems as $item) {
        if (!empty($item->current_price)) {
          $amo += $item->current_price*$item->quantity;
        } else {
          $amo += $item->price*$item->quantity;
        }
      }

      $char = 0;
      $coupon = Session::get('coupon_code');
      if(isset($coupon) && Coupon::where('coupon_code', $coupon)->count() == 1){
        $cdetails = Coupon::where('coupon_code', $coupon)->latest()->first();
        $data['cdetails'] = $cdetails;
        if ($cdetails->coupon_type == 'percentage'){
          $char = ($amo*$cdetails->coupon_amount)/100;
        }else{
          if($cdetails->coupon_min_amount <= $amo){
            $char = $cdetails->coupon_amount;
          }
        }
      }
      // return $char;
      $data['char']= $char;
      $data['amount']= $amo;



      $data['user'] = User::find(Auth::user()->id);
      $data['countries'] = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
      return view('user.checkout', $data);
    }


    public function couponvaliditycheck() {
      $today = new \Carbon\Carbon(Carbon::now());
      $coupons = Coupon::all();

      foreach ($coupons as $key => $coupon) {
        if ($today->gt(Carbon::parse($coupon->valid_till))) {
          if (session('coupon_code') == $coupon->coupon_code) {
            session()->forget('coupon_code');
          }
          $coupon->delete();
        }
      }


    }


    public function applycoupon(Request $request) {
      $gs = GS::first();

      $cartItems = Cart::where('cart_id', Auth::user()->id)->get();
      $amo = 0;
      foreach ($cartItems as $item) {
        if (!empty($item->current_price)) {
          $amo += $item->current_price*$item->quantity;
        } else {
          $amo += $item->price*$item->quantity;
        }
      }

      $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

      $validator = Validator::make($request->all(), [
          'coupon_code' => [
              'required',
              function ($attribute, $value, $fail) use ($request, $amo, $coupon) {
                  if (Coupon::where('coupon_code', $request->coupon_code)->count() == 0) {
                    $fail("Coupon code didn't match!");
                  } else {
                    if ($coupon->coupon_type == 'fixed' && $coupon->coupon_min_amount >= $amo) {
                      $fail("Your minimum cart total must be ".$coupon->coupon_min_amount);
                    }
                  }
              },
          ]
      ]);

      if($validator->fails()) {
          // adding an extra field 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
      }

      session()->forget('coupon_code');
      session()->put('coupon_code', $request->coupon_code);
      $csession = session('coupon_code');

      $cdetails = Coupon::where('coupon_code', $csession)->first();
      $ctotal = 0;
      if (session()->has('coupon_code') && !empty($cdetails)) {
        if ($cdetails->coupon_type == 'percentage') {
          $ctotal = ($cdetails->coupon_amount * $amo)/100;
        } else {
          $ctotal = $cdetails->coupon_amount;
        }
      }

      $subtotal = getSubTotal(Auth::user()->id);
      $total = getTotal(Auth::user()->id);

      return response()->json(['total'=>$total, 'subtotal'=>$subtotal, 'ctotal'=>$ctotal]);

    }

    public function placeorder(Request $request) {
      $gs = GS::first();

      // return $request;
      $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'address' => 'required',
        'country' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip_code' => 'required',
        'terms_helper' => [
          function ($attribute, $value, $fail) use ($request) {
              if (!$request->has('terms')) {
                return $fail('You must accept our terms & conditions');
              }
          },
        ]
      ]);

      if (Cart::where('cart_id', Auth::user()->id)->count() == 0) {
        Session::flash('alert', 'No product added to cart!');
        return back();
      }

      $gs = GS::first();
      // store in order table
      // $in = $request->except('_token', 'coupon_code', 'terms', 'terms_helper');
      $in['user_id'] = Auth::user()->id;
      $in['first_name'] = $request->first_name;
      $in['last_name'] = $request->last_name;
      $in['phone'] = $request->phone;
      $in['email'] = $request->email;
      $in['address'] = $request->address;
      $in['country'] = $request->country;
      $in['state'] = $request->state;
      $in['city'] = $request->city;
      $in['zip_code'] = $request->zip_code;
      $in['order_notes'] = $request->order_notes;
      $in['subtotal'] = getSubTotal(Auth::user()->id);
      $in['total'] = getTotal(Auth::user()->id);
      $in['place'] = $request->place;
      $pm = $request->payment_method;
      $place = $request->place;

      // if payment method is cash on delivery
      if ($pm == 1) {
        if ($place == 'in') {
          $scharge = $gs->in_cash_on_delivery;
        } elseif ($place == 'around') {
          $scharge = $gs->around_cash_on_delivery;
        } else {
          $scharge = $gs->world_cash_on_delivery;
        }
      }
      // if payment method is cash on advance
      else {
        if ($place == 'in') {
          $scharge = $gs->in_advanced;
        } elseif ($place == 'around') {
          $scharge = $gs->around_advanced;
        } else {
          $scharge = $gs->world_advanced;
        }
      }

      $in['shipping_charge'] = $scharge;
      $in['tax'] = $gs->tax;
      $in['payment_method'] = $pm;
      $in['shipping_method'] = $place;
      $order = Order::create($in);
      $order->unique_id = $order->id + 100000;
      $order->save();

      $carts = Cart::where('cart_id', Auth::user()->id)->get();


      // store products in orderedproducts table
      foreach($carts as $cart) {
        $product = Product::select('vendor_id')->where('id', $cart->product_id)->first();
        $op = new Orderedproduct;
        $op->user_id = Auth::user()->id;
        $op->order_id = $order->id;
        $op->vendor_id = $product->vendor_id;
        $op->product_id = $cart->product_id;
        $op->product_name = $cart->title;
        $op->product_price = $cart->price;
        $op->offered_product_price = $cart->current_price;

        $op->attributes = $cart->attributes;

        if (session()->has('coupon_code') && Coupon::where('coupon_code', session('coupon_code'))->count()==1) {
          $csession = session('coupon_code');
          $coupon = Coupon::where('coupon_code', $csession)->first();


          if ($coupon->coupon_type=='percentage') {
            // if coupon type is percentage

            if (empty($cart->current_price)) {
              // if the product has no offer...
              $cartItemTotal = $cart->quantity*$cart->price;
              $cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
              $producttotal = $cartItemTotal - $cartItemCoupon;
            } else {
              // if the product has offer...
              $cartItemTotal = $cart->quantity*$cart->current_price;
              $cartItemCoupon = ($cartItemTotal*$coupon->coupon_amount)/100;
              $producttotal = $cartItemTotal - $cartItemCoupon;
            }

          }
          else {
            // if coupon type is fixed

            $cartItems = Cart::where('cart_id', Auth::user()->id)->get();
            $amo = 0;
            foreach ($cartItems as $item) {
              if (!empty($item->current_price)) {
                $amo += $item->current_price*$item->quantity;
              } else {
                $amo += $item->price*$item->quantity;
              }
            }

            $charpertaka = $coupon->coupon_amount/$amo;


            if (empty($cart->current_price)) {
              $cartItemTotal = $cart->quantity*$cart->price;
              $cartItemCoupon = $cartItemTotal*$charpertaka;
              $producttotal = $cartItemTotal-$cartItemCoupon;
            } else {
              $cartItemTotal = $cart->quantity*$cart->current_price;
              $cartItemCoupon = $cartItemTotal*$charpertaka;
              $producttotal = $cartItemTotal-$cartItemCoupon;
            }

          }
        } else {
          if (empty($cart->current_price)) {
            // if cart item has no offer

            $producttotal = $cart->price*$cart->quantity;
            $cartItemCoupon = 0;
          } else {
            // if cart item has offer

            $producttotal = $cart->current_price*$cart->quantity;
            $cartItemCoupon = 0;
          }
        }

        $op->quantity = $cart->quantity;
        $op->product_total = $producttotal;
        $op->coupon_amount = $cartItemCoupon;
        $op->save();
      }


      if ($request->payment_method == 1) {
        // clear coupon from session
        session()->forget('coupon_code');
        // clear cart...
        Cart::where('cart_id', Auth::user()->id)->delete();
        // clear conditions (shipping)...
        PlacePayment::where('cart_id', Auth::user()->id)->delete();

        $message = "Your order has been placed successfully! Our agent will contact with you later. <br><strong>Order ID: </strong> " . $order->unique_id ."<p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>";

        send_email( $order->user->email, $order->user->first_name, "Order placed", $message);
        send_sms( $order->user->phone, $message);

        Session::flash('success', 'Order placed successfully! Our agent will contact with you later.');
        return redirect()->route('user.orders');
      } elseif ($request->payment_method == 2) {
        // redirect to payment gateway page
        return redirect()->route('user.gateways', $order->id);
        // after payment clear Cart and redirect to success page
      }
    }

    public function success() {
      return view('user.order_success');
    }
}
