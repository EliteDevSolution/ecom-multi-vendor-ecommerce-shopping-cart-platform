<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Subcategory;
use App\Orderedproduct;
use App\FlashInterval;
use Carbon\Carbon;
use App\Ad;
use DB;


class SearchController extends Controller
{
    public function search(Request $request, $category=null, $subcateogry=null) {

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

      $productids = [];
      $reqattrs = $request->except('maxprice', 'minprice', 'sort_by', 'term', 'page', 'type');
      $count = 0;
      if ($reqattrs) {
        $data['reqattrs'] = $reqattrs;
      } else {
        $data['reqattrs'] = [];
      }

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
      $type = $request->type;
      $data['sortby'] = $request->sort_by;
      $term = $request->term;
      $data['term'] = $request->term;

      // return $category;
      // return $subcategory;

      $data['minprice'] = Product::min('price');
      $data['maxprice'] = Product::max('price');

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
                      ->when($type, function ($query, $type)  {
                        if ($type == 'special') {
                          return $query->whereNotNull('current_price');
                        }
                      })
                      ->when(!$sortby, function ($query, $sortby)  {
                          return $query->orderBy('id', 'DESC');
                      })
                      ->when($term, function ($query, $term)  {
                          return $query->where('title', 'like', '%'.$term.'%');
                      })
                      ->when($productids, function ($query, $productids)  {
                          return $query->whereIn('id', $productids);
                      })
                      ->where('deleted', 0)->paginate(12);

      $data['categories'] = Category::where('status', 1)->get();

      $data['shopad'] = Ad::where('size', 3)->inRandomOrder()->first();

      return view('user.search', $data);
    }


}
