@extends('layout.master')

@section('title', 'Manage Orders')

@section('headertxt', 'Manage Orders')

@push('styles')
<link rel="stylesheet" href="{{asset('assets/user/css/vendor-orders.css')}}">
@endpush

@section('content')
  <!-- sellers product content area start -->
  <div class="sellers-product-content-area">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="order-track-page-content">
                    <div class="track-order-form-wrapper"><!-- track order form -->
                        <h3 class="title">Track Your Order From Here</h3>
                        <form action="{{route('vendor.orders')}}" class="track-order-form" method="get">
                            <div class="form-element">
                                <input name="order_number" type="text" value="{{request()->input('order_number')}}" class="input-field" placeholder="Type your order number...">
                            </div>
                            <button type="submit" class="submit-btn"><i class="fas fa-truck"></i> Track order</button>
                        </form>
                    </div><!-- //. track order form -->
                  </div>
                  <div class="seller-product-wrapper">
                      <div class="seller-panel">
                          <div class="sellers-product-inner">
                              <div class="bottom-content">
                                <table class="table table-default" id="datatableOne">
                                    <thead>
                                      <tr>
                                          <th>Order id</th>
                                          <th>Order Date</th>
                                          <th>Total</th>
                                          <th>Shipping Status</th>
                                          <th>Order Status</th>
                                          <th>Payment Method</th>
                                          <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($orders as $key => $order)
                                        <tr>
                                            <td class="padding-top-40">{{$order->unique_id}}</td>
                                            <td class="padding-top-40">{{date('jS F, o', strtotime($order->created_at))}}</td>
                                            <td class="padding-top-40">{{$gs->base_curr_symbol}} {{$order->total}}</td>
                                            <td class="padding-top-40">
                                              @if ($order->shipping_status == 0)
                                                <span class="badge badge-danger">Pending</span>
                                              @elseif ($order->shipping_status == 1)
                                                <span class="badge badge-warning">In Process</span>
                                              @elseif ($order->shipping_status == 2)
                                                <span class="badge badge-success">Shipped</span>
                                              @endif
                                            </td>
                                            <td class="padding-top-40">
                                              @if ($order->approve == 0)
                                                <span class="badge badge-warning">Pending</span>
                                              @elseif ($order->approve == 1)
                                                <span class="badge badge-success">Accepted</span>
                                              @elseif ($order->approve == -1)
                                                <span class="badge badge-danger">Rejected</span>
                                              @endif
                                            </td>
                                            <td class="padding-top-40">
                                              @if ($order->payment_method == 2)
                                                Advance
                                              @elseif ($order->payment_method == 1)
                                                Cash on delivery
                                              @endif
                                            </td>
                                            <td class="padding-top-40">
                                                <a class="btn btn-primary" href="{{route('vendor.orderdetails', $order->id)}}" target="_blank"><i class="text-white fa fa-eye"></i> view</a>
                                            </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                </table>

                                <div class="text-center">
                                    {{$orders->links()}}
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- sellers product content area end -->


@endsection
