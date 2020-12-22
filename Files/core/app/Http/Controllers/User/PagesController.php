<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting as GS;
use App\Product;
use App\ProductReview;
use App\Orderedproduct;
use App\Partner;
use Carbon\Carbon;
use App\Menu;
use App\Subscriber;
use App\FlashInterval;
use App\Ad;
use Cart;
use Auth;
use DB;
use Session;
use Validator;
use App\Slider;

class PagesController extends Controller
{
    public function home() {
      // return session()->get('browserid');
      $gs = GS::first();
      $data['lproducts'] = Product::where('deleted', 0)->orderBy('id', 'DESC')->limit(8)->get();


      // fetch top sold products
      $topSoldPros = Product::where('deleted', 0)->orderBy('sales', 'DESC')->limit(8)->get();
      $data['topSoldPros'] = $topSoldPros;

      // fetch top rated products
      $topRatedPros = Product::where('deleted', 0)->orderBy('avg_rating', 'DESC')->limit(8)->get();
      $data['topRatedPros'] = $topRatedPros;


      // fetch special products
      $data['specialPros'] = Product::whereNotNull('current_price')->where('deleted', 0)->orderBy('id', 'DESC')->limit(8)->get();

      // fetch recent products
      $data['latestPros'] = Product::where('deleted', 0)->orderBy('id', 'DESC')->limit(8)->get();

      // partners
      $data['partners'] = Partner::all();

      $data['sliders'] = Slider::all();


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
        $data['flashsales'] = $flashsales;
      } else {
        $data['flashsales'] = [];
      }


      return view('user.home', $data);

    }

    public function bestsellers() {
        $data['products'] = Product::orderBy('sales', 'DESC')->limit(12)->get();
      return view('user.best_seller', $data);
    }

    public function contact() {
      return view('user.contact');
    }

    public function terms() {
      return view('user.tos');
    }

    public function privacy() {
      return view('user.privacy');
    }

    public function contactMail(Request $request) {
      $validatedRequest = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'subject' => 'required',
        'message' => 'required',
      ]);

      $gs = GS::first();
      $from = $request->email;
      $to = $gs->email_sent_from;
      $subject = $request->subject;
      $name = $request->name;
      $message = nl2br($request->message . "<br /><br />From <strong>" . $name . "</strong>");


      $headers = "From: $name <$from> \r\n";
      $headers .= "Reply-To: $name <$from> \r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


       mail($to, $subject, $message, $headers);
      Session::flash('success', 'Mail sent successfully!');
      return redirect()->back();
    }

    public function dynamicPage($slug) {
      $data['menu'] = Menu::where('slug', $slug)->first();
      return view('user.dynamic', $data);
    }

    public function subscribe(Request $request) {
      $validator = Validator::make($request->all(), [
          'email' => 'required|email|unique:subscribers'
      ]);

      if ($validator->fails()) {
        $validator->getMessageBag()->add('error', 'true');
        return response()->json($validator->errors());
      }

      $subsc = new Subscriber;
      $subsc->email = $request->email;
      $subsc->save();
      return "success";
    }

    public function flashsalecheck() {
      // next count_down_to time calculation
      $time = Carbon::now()->format('H:i');
      $fi = FlashInterval::whereTime('start_time', '<=', $time)->whereTime('end_time', '>', $time)->first();

      // if current time is in flash interval
      if (!empty($fi)) {
        $fi_id = $fi->id;
        $today = new \Carbon\Carbon(Carbon::now());
        $flashsales = Product::whereDate('flash_date', $today)->where('flash_status', 1)->where('flash_interval', $fi_id)->where('flash_div_refresh', 0)->orderBy('flash_request_date', 'DESC')->limit(8)->get();
        // if flashsales products exists without refresh
        if (count($flashsales) > 0) {
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

          $endtime = $fi->end_time;
          $date = date('M j, Y', strtotime($today));
          $countto = $date.' '.$endtime.':00';

          $flashsales = Product::whereDate('flash_date', $today)->where('flash_status', 1)->where('flash_interval', $fi_id)->orderBy('flash_request_date', 'DESC')->limit(8)->get();

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

          $status = 1;

          return response()->json(['status'=>$status, 'flashsales' => $flashsales, 'countto'=>$countto]);
        } else {
          $status = 0;
        }

    } else {
      $status = 0;
    }

    return response()->json(['status'=>$status]);
  }
}
