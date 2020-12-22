@extends('admin.layout.master')

@push('styles')
<style media="screen">
  .product-img img {
    width: 100%;
  }
  .thumb img {
      max-width: 70px;
      float: left;
      margin-right: 12px;
  }
  .title {
    font-size: 16px;
  }
  .order-summary span.left {
      font-weight: 700;
  }

  .order-summary span.right {
      float: right;
  }
  .order-summary ul {
      padding: 0px;
      list-style-type: none;
  }
  .order-summary li {
      margin-bottom: 20px;
  }

  .order-summary .total {
      font-weight: 900;
      color: #373737;
  }
  .order-summary li:last-child {
      margin-bottom: 0px;
      border-top: 1px solid #ddd;
      padding-top: 20px;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold"> <i class="fa fa-desktop"></i> All Orders</h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
              <!-- sellers product content area start -->
              <div class="sellers-product-content-area">
                  <div class="">
                    <div class="row mb-2">
                      <div class="col-md-12">
                        <h2 style="font-size: 32px;margin-bottom: 28px;" class="order-heading">Order Information</h2>
                      </div>
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-header bg-primary text-white">
                            <h6 class="white-txt no-margin">Order ID # {{$order->unique_id}}</h6>
                          </div>
                          <div class="card-body">
                            <p>
                              <strong>Order Status: </strong>
                              @if ($order->approve == 0)
                                <span class="badge badge-warning">Pending</span>
                              @elseif ($order->approve == 1)
                                <span class="badge badge-success">Accpeted</span>
                              @elseif ($order->approve == -1)
                                <span class="badge badge-danger">Rejected</span>
                              @endif
                            </p>
                            <p><strong>Order Date: </strong> {{date('jS F, Y', strtotime($order->created_at))}}</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-header bg-primary text-white">
                            <h6 class="white-txt no-margin">Payment / Shipping Method</h6>
                          </div>
                          <div class="card-body">
                            <p>
                              <strong>Shipping Method: </strong>
                              @if ($order->shipping_method == 'around')
                                Arround {{$gs->main_city}}
                              @elseif ($order->shipping_method == 'world')
                                Arround the World
                              @elseif ($order->shipping_method == 'in')
                                In {{$gs->main_city}}
                              @endif
                            </p>
                            <p>
                              <strong>Payment Method: </strong>
                              @if ($order->payment_method == 1)
                                Cash on delivery
                              @elseif ($order->payment_method == 2)
                                Advance Paid via <strong>{{$order->orderpayment->gateway->name}}</strong>
                              @endif
                            </p>
                            @if ($order->approve != -1)
                              <p>
                                <strong>Shipping Status: </strong>
                                @if ($order->shipping_status == 0)
                                  <span class="badge badge-danger">Pending</span>
                                @elseif ($order->shipping_status == 1)
                                  <span class="badge badge-warning">In Process</span>
                                @elseif ($order->shipping_status == 2)
                                  <span class="badge badge-success">Shipped</span>
                                @endif
                              </p>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-4">
                      @if (!empty($order->user->billing_last_name))
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-header bg-primary text-white">
                              <h6 class="white-txt no-margin">Biling Details</h6>
                            </div>
                            <div class="card-body">
                              <p><strong>{{$order->first_name}} {{$order->user->billing_last_name}}</strong></p>
                              <p><strong>Email: </strong>{{$order->user->billing_email}}</p>
                              <p><strong>Phone: </strong>{{$order->user->billing_phone}}</p>
                              <p><strong>Address: </strong>{{$order->user->billing_address}}</p>
                            </div>
                          </div>
                        </div>
                      @else
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-header bg-primary text-white">
                              <h6 class="white-txt no-margin">Biling Details</h6>
                            </div>
                            <div class="card-body">
                              <p><strong>{{$order->first_name}} {{$order->last_name}}</strong></p>
                              <p><strong>Email: </strong>{{$order->email}}</p>
                              <p><strong>Phone: </strong>{{$order->phone}}</p>
                              <p><strong>Address: </strong>{{$order->address}}</p>
                            </div>
                          </div>
                        </div>
                      @endif
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-header bg-primary text-white">
                            <h6 class="white-txt no-margin">Shipping Details</h6>
                          </div>
                          <div class="card-body">
                            <p><strong>{{$order->first_name}} {{$order->last_name}}</strong></p>
                            <p><strong>Email: </strong>{{$order->email}}</p>
                            <p><strong>Phone: </strong>{{$order->phone}}</p>
                            <p><strong>Address: </strong>{{$order->address}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    @if (!empty($order->order_notes))
                    <div class="row mb-4">
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-header bg-primary text-white">
                            <h6 class="white-txt no-margin">Order Note</h6>
                          </div>
                          <div class="card-body">
                              <p>{{$order->order_notes}}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                      <div class="row">
                          <div class="col-lg-8">
                              <div class="seller-product-wrapper">
                                  <div class="seller-panel">
                                      <div class="sellers-product-inner">
                                          <div class="table-responsive">
                                              <table class="table table-bordered" id="datatableOne">
                                                  <thead class="bg-primary text-white">
                                                      <tr>
                                                          <th>Product</th>
                                                          <th>Product Code</th>
                                                          <th>Price</th>
                                                          <th>Quantity</th>
                                                          <th>Total</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                    @foreach ($orderedproducts as $key => $orderedproduct)
                                                      <tr>
                                                          <td>
                                                              <div class="single-product-item"><!-- single product item -->
                                                                  <div class="thumb">
                                                                      <img src="{{asset('assets/user/img/products/'.$orderedproduct->product->previewimages()->first()->image)}}" alt="seller product image">
                                                                  </div>
                                                                  <div class="content" style="padding-top:0px;">
                                                                      <h4 class="title"><a href="{{route('user.product.details', [$orderedproduct->product->slug, $orderedproduct->product->id])}}" target="_blank">{{strlen($orderedproduct->product->title) > 25 ? substr($orderedproduct->product->title, 0, 25) . '...' : $orderedproduct->product->title}}</a></h4>
                                                                      @php
                                                                        $attrs = json_decode($orderedproduct->attributes, true);
                                                                        // dd($attrs);
                                                                      @endphp
                                                                      @if (!empty($attrs))
                                                                        <p>
                                                                          @foreach ($attrs as $attrname => $attr)
                                                                              <strong>{{str_replace("_", " ", $attrname)}}:</strong>
                                                                              @foreach ($attr as $sattr)

                                                                                @if (!$loop->last)
                                                                                  {{$sattr}},
                                                                                @else
                                                                                  {{$sattr}}
                                                                                @endif
                                                                              @endforeach
                                                                              @if (!$loop->last)
                                                                                |
                                                                              @endif
                                                                          @endforeach
                                                                        </p>
                                                                      @endif
                                                                  </div>
                                                              </div><!-- //.single product item -->
                                                          </td>
                                                          <td class="">{{$orderedproduct->product->product_code}}</td>
                                                          <td class="">
                                                            @if (!empty($orderedproduct->offered_product_price))
                                                              <del>{{$gs->base_curr_symbol}} {{round($orderedproduct->product_price, 2)}}</del><br>
                                                              <span>{{$gs->base_curr_symbol}} {{round($orderedproduct->offered_product_price, 2)}}</span>
                                                            @else
                                                                <span>{{$gs->base_curr_symbol}} {{round($orderedproduct->product->price, 2)}}</span>
                                                            @endif
                                                          </td>
                                                          <td class="">{{$orderedproduct->quantity}}</td>
                                                          <td class="">
                                                            @if (!empty($orderedproduct->offered_product_price))
                                                              <del>{{$gs->base_curr_symbol}} {{round($orderedproduct->product_price, 2)*$orderedproduct->quantity}}</del><br>
                                                              <span>{{$gs->base_curr_symbol}} {{round($orderedproduct->offered_product_price, 2)*$orderedproduct->quantity}}</span>
                                                            @else
                                                                <span>{{$gs->base_curr_symbol}} {{round($orderedproduct->product->price, 2)*$orderedproduct->quantity}}</span>
                                                            @endif
                                                          </td>
                                                      </tr>
                                                    @endforeach
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-4">
                            <div class="card order-summary">
                              <div class="card-header bg-primary text-white">
                                <h4 class="white-txt no-margin">Order Summary</h4>
                              </div>
                              <div class="card-body">
                                @php
                                  $ccharge = \App\Orderedproduct::where('order_id', $order->id)->sum('coupon_amount');
                                @endphp
                                <ul>
                                  <li><span class="left">CART AMOUNT</span> <span class="right">{{$gs->base_curr_symbol}} {{$order->subtotal + $ccharge}}</span></li>
                                  @if (\App\Orderedproduct::where('order_id', $order->id)->sum('coupon_amount') > 0)
                                    <li><span class="left">COUPON (Discount)</span> <span class="right">- {{$gs->base_curr_symbol}} {{round($ccharge, 2)}}</span></li>
                                  @else
                                    <li><span class="left">COUPON (Discount)</span> <span class="right">- {{$gs->base_curr_symbol}} 0.00</span></li>
                                  @endif
                                  <li><span class="left">SUBTOTAL</span> <span class="right">{{$gs->base_curr_symbol}} {{$order->subtotal}}</span></li>
                                  <li><span class="left">TAX</span> <span class="right">{{$gs->base_curr_symbol}} {{$order->subtotal*($gs->tax/100)}}</span></li>
                                  <li><span class="left">SHIPPING COST</span> <span class="right">{{$gs->base_curr_symbol}} {{$order->shipping_charge}}</span></li>

                                  <li class="li-total"><span class="left total">TOTAL</span> <span class="right total">{{$gs->base_curr_symbol}} {{$order->total}}</span></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- sellers product content area end -->
        </div>
     </div>
   </div>
  </main>

@endsection
