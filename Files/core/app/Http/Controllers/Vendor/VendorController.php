<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orderedproduct as OP;
use App\Product;
use App\Order;
use App\Orderedproduct;
use App\Vendor;
use App\ProductReview;
use App\Transaction;
use DB;
use Auth;
use App\Category;
use App\Subcategory;
use Carbon\Carbon;
use App\Ad;
use Hash;
use Validator;
use Session;


class VendorController extends Controller
{
    public function dashboard() {
      $topSoldOps = OP::where('approve', 1)
                       ->where('refunded', '<>', 1)
                       ->select(DB::raw("sum(quantity) as sales, product_id"))
                       ->groupBy('product_id')->orderBy('sales', 'DESC')->limit(10)->get();
      $products = [];
      foreach ($topSoldOps as $key => $topSoldOp) {
        $products[] = Product::find($topSoldOp->product_id);
      }
      $data['products'] = $products;
      $topAvgRatings = ProductReview::select(DB::raw('avg(rating) as rating, product_id'))->groupBy('product_id')->limit(10)->orderBy('rating', 'DESC')->get();
      $topRatedPros = [];
      foreach ($topAvgRatings as $key => $topAvgRating) {
        $topRatedPros[] = Product::find($topAvgRating->product_id);
      }
      $data['topRatedPros'] = $topRatedPros;

      $orderids = Order::join('orderedproducts', 'orders.id', '=', 'orderedproducts.order_id')->select('orders.id')->groupBy('orders.id')->where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'DESC')->limit(10)->get();
      $orderidarr = [];
      foreach ($orderids as $key => $orderid) {
        $orderidarr[] = $orderid->id;
      }
      $data['lorders'] = Order::whereIn('id', $orderidarr)->orderBy('id', 'DESC')->paginate(10);


      return view('vendor.dashboard', $data);
    }

    public function shoppage(Request $request, $vendorid, $category=null, $subcateogry=null) {
      $data['vendor'] = Vendor::find($vendorid);
      $productids = [];
      $reqattrs = $request->except('maxprice', 'minprice', 'sort_by', 'page', 'term');
      $count = 0;
      if ($reqattrs) {
        $data['reqattrs'] = $reqattrs;
      } else {
        $data['reqattrs'] = [];
      }

      // foreach ($reqattrs as $key => $reqattr) {
      //   return $reqattrs[$key]; // Array[2] 0:"M" 1:"L"
      //   foreach ($reqattr as $key => $ra) {
      //     return $ra; // M
      //   }
      // }

      $products = Product::where('subcategory_id', $subcateogry)->get();
      // return $products;
      // return count($reqattrs);

      foreach ($products as $key => $product) {
        $proattrs = json_decode($product->attributes, true);
        $count = 0;

        foreach ($proattrs as $key => $proattr) {
          // return $proattrs[$key]; //Array[3] 0:"M" 1:"L" 2:"XL"

          if (!empty($reqattrs[$key])) {
            if (!empty(array_intersect($reqattrs[$key], $proattrs[$key]))) {
              $count++;
            }
          }
        }

        if ($count == count($reqattrs)) {
          $productids[] = $product->id;
        }
      }

      // return $productids;

      $category = $request->category;
      $subcategory = $request->subcategory;
      $minprice = $request->minprice;
      $maxprice = $request->maxprice;
      $sortby = $request->sort_by;
      $data['sortby'] = $request->sort_by;
      $term = $request->term;
      $data['term'] = $request->term;

      // return $category;
      // return $subcategory;

      $data['minprice'] = Product::where('vendor_id', $vendorid)->min('price');
      $data['maxprice'] = Product::where('vendor_id', $vendorid)->max('price');

      $data['products'] = Product::when($category, function ($query, $category) {
                          return $query->where('category_id', $category);
                      })
                      ->when($subcategory, function ($query, $subcategory)  {
                          return $query->where('subcategory_id', $subcategory);
                      })
                      ->when($minprice, function ($query, $minprice)  {
                          return $query->where('price', '>=', $minprice);
                      })
                      ->when($maxprice, function ($query, $maxprice)  {
                          return $query->where('price', '<=', $maxprice);
                      })
                      ->when($sortby, function ($query, $sortby)  {
                        if ($sortby == 'date_desc') {
                          return $query->orderBy('created_at', 'DESC');
                        } elseif ($sortby == 'date_asc') {
                          return $query->orderBy('created_at', 'ASC');
                        } elseif ($sortby == 'price_desc') {
                          return $query->orderBy('search_price', 'DESC');
                        } elseif ($sortby == 'price_asc') {
                          return $query->orderBy('search_price', 'ASC');
                        } elseif ($sortby == 'sales_desc') {
                          return $query->orderBy('sales', 'DESC');
                        } elseif ($sortby == 'rate_desc') {
                          return $query->orderBy('avg_rating', 'DESC');
                        }

                      })
                      ->when($term, function ($query, $term)  {
                          return $query->where('title', 'like', '%'.$term.'%');
                      })
                      ->when($productids, function ($query, $productids)  {
                          return $query->whereIn('id', $productids);
                      })
                      ->where('deleted', 0)->where('vendor_id', $vendorid)->paginate(12);

      $data['categories'] = Category::where('status', 1)->get();

      $data['vendorid'] = $vendorid;

      $data['shopad'] = Ad::where('size', 3)->inRandomOrder()->first();

      return view('vendor.shop_page', $data);
    }

    public function changePassword(Request $request) {
      return view('vendor.changePassword');
    }

    public function updatePassword(Request $request) {
        $messages = [
            'password.required' => 'The new password field is required',
            'password.confirmed' => "Password does'nt match"
        ];

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ], $messages);
        // if given old password matches with the password of this authenticated user...
        if(Hash::check($request->old_password, Auth::user()->password)) {
            $oldPassMatch = 'matched';
        } else {
            $oldPassMatch = 'not_matched';
        }
        if ($validator->fails() || $oldPassMatch=='not_matched') {
            if($oldPassMatch == 'not_matched') {
              $validator->errors()->add('oldPassMatch', true);
            }
            return redirect()->back()->withErrors($validator);
        }

        // updating password in database...
        $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
        $vendor->password = bcrypt($request->password);
        $vendor->save();

        Session::flash('success', 'Password changed successfully!');

        return redirect()->back();
    }

    public function transactions() {
      $data['transactions'] = Transaction::where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'DESC')->paginate(10);
      return view('vendor.transactions', $data);
    }

    public function couponlog(Request $request) {


      if ($request->to_date || $request->from_date || $request->order_id) {
        $toDate = Carbon::parse($request->to_date)->toDateString();
        $fromDate = Carbon::parse($request->from_date)->toDateString();
        $orderid = $request->order_id;

        $data['logs'] = Orderedproduct::where('vendor_id', Auth::guard('vendor')->user()->id)->whereNotNull('coupon_amount')
                                        ->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)
                                        ->when($orderid, function ($query, $orderid) {
                                            return $query->orWhere('order_id', $orderid+100000);
                                        })
                                        ->orderBy('id', 'DESC')->paginate(10);
        $data['ctotal'] = Orderedproduct::where('vendor_id', Auth::guard('vendor')->user()->id)->whereNotNull('coupon_amount')
                                        ->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate)
                                        ->when($orderid, function ($query, $orderid) {
                                            return $query->orWhere('order_id', $orderid+100000);
                                        })
                                        ->orderBy('id', 'DESC')->sum('coupon_amount');
      } else {
        $data['logs'] = Orderedproduct::where('vendor_id', Auth::guard('vendor')->user()->id)->whereNotNull('coupon_amount')->orderBy('id', 'DESC')->paginate(10);
        $data['ctotal'] = Orderedproduct::where('vendor_id', Auth::guard('vendor')->user()->id)->whereNotNull('coupon_amount')->sum('coupon_amount');
      }

      return view('vendor.couponlog', $data);
    }
}
