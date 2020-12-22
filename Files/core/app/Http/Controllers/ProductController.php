<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Cart;
use App\Favorit;
use App\PreviewImage;
use App\ProductReview;
use App\Orderedproduct;
use App\FlashInterval;
use Carbon\Carbon;
use Validator;
use Auth;
use Session;

class ProductController extends Controller
{
    public function show($slug=null, $id) {
      $today = new \Carbon\Carbon(Carbon::now());

      // bring their price back to pre price (without flash sale)
      $notflashsales = Product::where('flash_status', 1)->get();

      foreach ($notflashsales as $key => $notflashsale) {
        if (empty($notflashsale->offer_type)) {
          $notflashsale->current_price = NULL;
          $notflashsale->search_price = $notflashsale->price;
          $notflashsale->flash_div_refresh = 0;
          $notflashsale->save();

        } else {
          if ($notflashsale->offer_type == 'percent') {
            $notflashsale->current_price = $notflashsale->price - ($notflashsale->price*($notflashsale->offer_amount/100));
            $notflashsale->search_price = $notflashsale->current_price;
            $notflashsale->flash_div_refresh = 0;
            $notflashsale->save();
          } else {
            $notflashsale->current_price = $notflashsale->price - $notflashsale->offer_amount;
            $notflashsale->search_price = $notflashsale->current_price;
            $notflashsale->flash_div_refresh = 0;
            $notflashsale->save();
          }
        }
      }

      // next count_down_to time calculation
      $time = Carbon::now()->format('H:i');
      $fi = FlashInterval::whereTime('start_time', '<=', $time)->whereTime('end_time', '>', $time)->first();

      // if current time is in flash interval
      if ($fi) {
        $endtime = $fi->end_time;
        $fi_id = $fi->id;
        $date = date('M j, Y', strtotime($today));
        $data['countto'] = $date.' '.$endtime.':00';

        $flashsales = Product::whereDate('flash_date', $today)->where('flash_status', 1)->where('flash_interval', $fi_id)->orderBy('flash_request_date', 'DESC')->get();

        foreach ($flashsales as $key => $flashsale) {
          if ($flashsale->flash_type == 1) {
            $flashsale->current_price = $flashsale->price - ($flashsale->price*($flashsale->flash_amount/100));
            $flashsale->search_price = $flashsale->current_price;
            $flashsale->flash_div_refresh = 1;
            $flashsale->save();
          } else {
            $flashsale->current_price = $flashsale->price - $flashsale->flash_amount;
            $flashsale->search_price = $flashsale->current_price;
            $flashsale->flash_div_refresh = 1;
            $flashsale->save();
          }
        }
      }

      $data['product'] = Product::find($id);
      $data['sales'] = $data['product']->sales;
      $data['rproducts'] = Product::where('subcategory_id', $data['product']->subcategory_id)->where('deleted', 0)->inRandomOrder()->limit(10)->get();
      return view('product.show', $data);
    }

    public function getcomments(Request $request) {
      $reviews = ProductReview::where('product_id', $request->product_id)->orderBy('id', 'DESC')->get();
      return $reviews;
    }

    public function reviewsubmit(Request $request) {

      $validator = Validator::make($request->all(), [
        'rating' => [
          function ($attribute, $value, $fail) {
              if (empty($value)) {
                  $fail('Rating is required');
              }
          },
        ]
      ]);

      if($validator->fails()) {
          // adding an extra field 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
      }

      $productreview = new ProductReview;
      $productreview->user_id = Auth::user()->id;
      $productreview->product_id = $request->product_id;
      $productreview->rating = floatval($request->rating);
      $productreview->comment = $request->comment;
      $productreview->save();

      $product = Product::find($request->product_id);
      $product->avg_rating = ProductReview::where('product_id', $request->product_id)->avg('rating');
      $product->save();

      Session::flash('success', 'Reviewed successfully');

      return "success";
    }


    // add to cart...
    public function getproductdetails(Request $request) {
      if (Auth::check()) {
        $sessionid = Auth::user()->id;
      } else {
        $sessionid = session()->get('browserid');
      }

      // get details of the selected product
      $product = Product::find($request->productid);
      $preimg = PreviewImage::where('product_id', $product->id)->first();
      $product['preimg'] = $preimg->image;


      // if this product is already in the cart then just update the quantity...
      if (Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->count() > 0) {
        $cart = Cart::where('cart_id', $sessionid)->where('product_id', $product->id)->first();
        $cart->quantity = $cart->quantity + 1;
        $cart->save();
        return response()->json(['status'=>'quantityincr', 'productid'=>$product->id, 'quantity'=>$cart->quantity]);
      }


      // if a new product is added to cart
      $cart = new Cart;
      $cart->cart_id = $sessionid;
      $cart->product_id = $product->id;
      $cart->title = $product->title;
      $cart->price = $product->price;
      $cart->quantity = $request->quantity;
      $cart->save();

      $product['quantity'] = $request->quantity;
      return response()->json(['status'=>'productadded', 'product'=>$product, 'quantity'=>$product['quantity']]);
    }

    public function favorit(Request $request) {
      $count = Favorit::where('user_id', Auth::user()->id)->where('product_id', $request->productid)->count();
      if ($count > 0) {
        Favorit::where('user_id', Auth::user()->id)->where('product_id', $request->productid)->delete();
        return "unfavorit";
      } else {
        $favorit = new Favorit;
        $favorit->user_id = Auth::user()->id;
        $favorit->product_id = $request->productid;
        $favorit->save();
        return "favorit";
      }
    }

    public function productratings($pid) {
      $prs = ProductReview::where('product_id', $pid)->get();
      return $prs;
    }

    public function avgrating($pid) {
      $avgrating = ProductReview::where('product_id', $pid)->avg('rating');
      if (empty($avgrating)) {
        $avgrating = 0;
      }
      return $avgrating;
    }
}
