<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Vendor;
use App\Orderedproduct;
use App\Order;

class OrderController extends Controller
{
    public function orders(Request $request) {
      if ($request->order_number) {
        $orderids = Order::join('orderedproducts', 'orders.id', '=', 'orderedproducts.order_id')->select('orders.id')->groupBy('orders.id')->where('vendor_id', Auth::guard('vendor')->user()->id)->where('orders.unique_id', $request->order_number)->get();
        $orderidarr = [];
        foreach ($orderids as $key => $orderid) {
          $orderidarr[] = $orderid->id;
        }
        $data['orders'] = Order::whereIn('id', $orderidarr)->orderBy('id', 'DESC')->paginate(10);
      } else {
        $orderids = Order::join('orderedproducts', 'orders.id', '=', 'orderedproducts.order_id')->select('orders.id')->groupBy('orders.id')->where('vendor_id', Auth::guard('vendor')->user()->id)->get();
        $orderidarr = [];
        foreach ($orderids as $key => $orderid) {
          $orderidarr[] = $orderid->id;
        }
        $data['orders'] = Order::whereIn('id', $orderidarr)->orderBy('id', 'DESC')->paginate(10);
      }

      // return $data['orders'];
      return view('vendor.orders.orders', $data);
    }

    public function orderdetails($orderid) {
      $data['orderedproducts'] = Orderedproduct::where('order_id', $orderid)->where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'DESC')->get();
      $data['subtotal'] = 0;
      $data['ccharge'] = 0;
      foreach ($data['orderedproducts'] as $op) {
        if (empty($op->offered_product_price)) {
          $data['subtotal'] += $op->product_price*$op->quantity;
        } else {
          $data['subtotal'] += $op->offered_product_price*$op->quantity;
        }
        $data['ccharge'] += $op->coupon_amount;
      }
      $data['order'] = Order::find($orderid);
      $data['total'] = Orderedproduct::where('order_id', $orderid)->where('vendor_id', Auth::guard('vendor')->user()->id)->sum('product_total');
      $data['tax'] = ($data['order']->tax/100)*$data['subtotal'];
      return view('vendor.orders.details', $data);
    }

    public function shippingchange(Request $request) {
      $op = Order::find($request->orderid);
      $op->shipping_status = $request->value;
      $op->save();
      return "success";
    }

    public function acceptOrder(Request $request) {
      $order = Order::find($request->orderid);
      $order->approve = 1;
      $order->save();
      send_email($order->user->email, $order->user->first_name, 'Order accepted', "Your order has been accepted.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
      return "success";
    }

    public function cancelOrder(Request $request) {
      $order = Order::find($request->orderid);
      $order->approve = -1;
      $order->save();
      send_email($order->user->email, $order->user->first_name, 'Order rejected', "Your order has been rejected.<p><strong>Order Number: </strong>$order->unique_id</p><p><strong>Order details: </strong><a href='".url('/')."/".$order->id."/orderdetails'>".url('/')."/".$order->id."/orderdetails"."</a></p>");
      return "success";
    }

}
