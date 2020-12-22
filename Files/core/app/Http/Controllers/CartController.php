<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneralSetting as GS;
use App\Product;
use App\PreviewImage;
use App\Cart;
use App\CartCoupon;
use App\PlacePayment;
use Session;
use Auth;
use Validator;

class CartController extends Controller
{
    public function index() {
      $gs = GS::first();
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      if (empty($gs->coupon_code)) {
        if (CartCoupon::where('cart_id', $sessionid)->count() > 0) {
          CartCoupon::where('cart_id', $sessionid)->first()->delete();
        }
      }
      return view('cart.index');
    }


    public function getproductdetails(Request $request) {
      $product = Product::find($request->productid);

      // if removed then show a message to buyer
      if ($product->deleted == 1) {
        return response()->json(['status'=>'removed']);
      }

      if ($request->quantity > $product->quantity) {
        return response()->json(['status'=>'shortage', 'quantity' => $product->quantity]);
      }


      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }

      $selectedAttr = $request->except(['attribute_helper', 'productid', 'quantity', '_token']);
      $json_attr = json_encode($selectedAttr);

      $productattrs = Product::select('attributes')->find($request->productid);
      $attrarr = json_decode($productattrs->attributes, true);

      // if product has any attribute then attribute is required
      $validator = Validator::make($request->all(), [
          'attribute_helper' => [
              function ($attribute, $value, $fail) use ($attrarr, $request) {
                if (count($attrarr) > 0) {
                  foreach ($attrarr as $key => $value) {
                    if (!$request->has("$key")) {
                      $fail('All specifications are required');
                    }
                  }
                }
              },
          ],
      ]);

      if($validator->fails()) {
          // adding an extra field 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
      }

      // the products which has no attribute
      if ($product->attributes == '[]') {
        // if this product is already in the cart then just update the quantity...
        if (Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->count() > 0) {
          $cart = Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->first();
          $cart->quantity = $cart->quantity + $request->quantity;
          $cart->attributes = $json_attr;
          $cart->save();

          // subtract quantity from stock
          $product->quantity -= $request->quantity;
          $product->save();

          $stock = $product->quantity;
          $product['cart_id'] = $cart->id;
          $product['quantity'] = $request->quantity;

          return response()->json(['status'=>'quantityincr', 'product'=>$product, 'quantity'=>$cart->quantity, 'stock' => $stock]);
        }
      }


      // if a new product is added to cart
      $cart = new Cart;
      $cart->cart_id = $sessionid;
      $cart->product_id = $product->id;
      $cart->title = $product->title;
      $cart->price = $product->price;
      $cart->current_price = $product->current_price;
      $cart->quantity = $request->quantity;
      $cart->attributes = $json_attr;
      $cart->save();

      // subtract quantity from stock
      $product->quantity -= $request->quantity;
      $product->save();

      $stock = $product->quantity;

      // send image to show in sidebar cart
      $preimg = PreviewImage::where('product_id', $product->id)->first();
      $product['preimg'] = $preimg->image;


      $product['quantity'] = $request->quantity;
      $product['cart_id'] = $cart->id;

      $attrs = '';
      $j=0;
      foreach ($selectedAttr as $key => $values) {
        $attrs .= "".str_replace('_', ' ', $key).": ";
        $i = 0;
        foreach ($values as $v) {
          $attrs .= "$v";
          if (count($values)-1 != $i) {
            $attrs .= ", ";
          } else {
            $attrs .= " ";
          }
          $i++;
        }
        if (count($selectedAttr) - 1 != $j) {
          $attrs .= ' | ';
        }

        $j++;
      }

      $product['attrs'] = $attrs;

      $product['countattr'] = count($selectedAttr);

      return response()->json(['status'=>'productadded', 'product'=>$product, 'quantity'=>$product['quantity'], 'stock' => $stock]);
    }

    public function remove(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      $cart = Cart::find($request->cartid);
      $product = Product::find($cart->product_id);
      $product->quantity = $product->quantity + $cart->quantity;
      $product->save();

      $cart->delete();

      $quantity = Cart::where('cart_id', $sessionid)->sum('quantity');

      $total = getTotal($sessionid);
      $subtotal = getSubTotal($sessionid);

      return response()->json(['status' => 'removed', 'total' => $total, 'stock' => $product->quantity, 'quantity' => $quantity, 'subtotal' => $subtotal]);
    }

    public function update(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      $cart = Cart::where('cart_id', $sessionid);

      if (empty($request->removedpros)) {
        $request->removedpros = [];
      }

      foreach ($request->removedpros as $removedpro) {
        $cart = Cart::find($removedpro);
        $product = Product::find($cart->product_id);
        $product->quantity = $product->quantity + $cart->quantity;
        $product->save();
        $cart->delete();
      }


      $i = 0;
      $prices = [];
      $totalItems = 0;

      foreach (Cart::where('cart_id', $sessionid)->get() as $singlecart) {

        $product = Product::find($singlecart->product_id);

        if ($request->qts[$i] < $singlecart->quantity) {
          $product->quantity = $product->quantity + ($singlecart->quantity - $request->qts[$i]);
          $product->save();
        } elseif ($request->qts[$i] > $singlecart->quantity) {
          $product->quantity = $product->quantity - ($request->qts[$i] - $singlecart->quantity);
          $product->save();
        }

        $singlecart->quantity = $request->qts[$i];
        $singlecart->save();


        if (empty($singlecart->current_price)) {
          $prices[] = $singlecart->price * $singlecart->quantity;
        } else {
          $prices[] = $singlecart->current_price * $singlecart->quantity;
        }
        $i++;
      }

      $total = getTotal($sessionid);
      $subtotal = getSubTotal($sessionid);

      $totalItems = Cart::where('cart_id', $sessionid)->sum('quantity');

      return response()->json(['status' => 'success', 'prices'=>$prices, 'total' => $total, 'subtotal' => $subtotal, 'totalItems'=>$totalItems]);
    }

    public function getTotal(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }

      $gs = GS::first();
      $pm = $request->paymentMethod;
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

      // if there is no existing place and payment for this cart then store it
      $placePay = PlacePayment::where('cart_id', $sessionid);
      if ($placePay->count() == 0) {
        $newpp = new PlacePayment;
        $newpp->cart_id = $sessionid;
        $newpp->place = $place;
        $newpp->payment = $pm;
        $newpp->save();
      }
      // else update the cart with these place and payment
      else {
        $expp = $placePay->first();
        $expp->place = $place;
        $expp->payment = $pm;
        $expp->save();
      }

      // calculate total using tax and shipping charge...
      $total =  getTotal($sessionid);


      return response()->json(['total'=>$total, 'shippingcharge'=>$scharge]);
    }

    public function getcart(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      $cart = Cart::session($sessionid);

      $carts = $cart->getContent()->toJson();
      return $carts;
    }

    public function stockcheck(Request $request) {
      $cart = Cart::find($request->id);
      $product = Product::find($cart->product_id);
      $additional = ($request->quantity - $cart->quantity);
      $quantity = $product->quantity;

      if ($additional > $quantity) {
        return response()->json(['status' => 'shortage', 'quantity' => $product->quantity]);
      }

      return response()->json(['status' => 'available', 'quantity' => $product->quantity]);
    }

    public function clearcart(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }
      $cart = Cart::session($sessionid);

      $cart->clear();
      return 'cleared';
    }
}
