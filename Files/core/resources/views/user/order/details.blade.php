@extends('layout.master')

@section('title', 'Ordered Products')

@section('headertxt', "Order #$order->unique_id")

@push('styles')
<link rel="stylesheet" href="{{asset('assets/user/css/uorder-details.css')}}">
@endpush

@section('content')
  <!-- sellers product content area start -->
  <div class="sellers-product-content-area">
      <div class="container">
        <div class="row mb-2">
          <div class="col-md-12">
            <h2 style="font-size: 32px;margin-bottom: 28px;" class="order-heading">Order Information</h2>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header base-bg">
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
              <div class="card-header base-bg">
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
                <div class="card-header base-bg">
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
                <div class="card-header base-bg">
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
              <div class="card-header base-bg">
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
              <div class="card-header base-bg">
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
              <div class="col-xl-9">
                  <div class="seller-product-wrapper">
                      <div class="seller-panel">
                          <div class="sellers-product-inner">
                              <div class="bottom-content">
                                  <table class="table table-default" id="datatableOne">
                                      <thead>
                                          <tr>
                                              <th>Product</th>
                                              <th>Product Code</th>
                                              <th>Price</th>
                                              <th>Quantity</th>
                                              <th>Total</th>
                                              @if ($order->shipping_status == 2)
                                              <th>Action</th>
                                              @endif
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
                                              @if ($orderedproduct->order->shipping_status == 2)
                                                <td>
                                                  <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#commentModal{{$orderedproduct->id}}">Comment</button>
                                                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#refundModal{{$orderedproduct->id}}">Refund</button>
                                                </td>
                                              @endif
                                          </tr>


                                          {{-- Comment Modal --}}
                                          <div class="modal fade" id="commentModal{{$orderedproduct->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">{{$orderedproduct->product->title}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <input id="opid{{$orderedproduct->id}}" type="hidden" name="opid" value="{{$orderedproduct->id}}">
                                                    <div class="form-group">
                                                      <label for="" style="color:#000;"><strong>Comment Type: </strong></label>
                                                      <select id="comment_type{{$orderedproduct->id}}" class="form-control" name="comment_type">
                                                        <option value="Complain" {{(empty($orderedproduct->comment_type) || $orderedproduct->comment_type=='Complain') ? 'selected' : ''}}>Complain</option>
                                                        <option value="Suggestion" {{$orderedproduct->comment_type=='Suggestion' ? 'selected' : ''}}>Suggestion</option>
                                                      </select>
                                                    </div>
                                                    <div class="form-group">
                                                      <label for="" style="color:#000;"><strong>Comment: </strong></label>
                                                      <textarea id="comment{{$orderedproduct->id}}" class="form-control" name="comment" rows="5" cols="80" placeholder="Please write your comment" {{!empty($orderedproduct->comment) ? 'readonly' : ''}} required>{{$orderedproduct->comment}}</textarea>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    @if (empty($orderedproduct->comment))
                                                      <button type="button" class="btn base-bg text-white" onclick="complain({{$orderedproduct->id}})">Submit</button>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                  </div>

                                              </div>
                                            </div>
                                          </div>


                                          {{-- Renfund Modal --}}
                                          <div class="modal fade" id="refundModal{{$orderedproduct->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">{{$orderedproduct->product->title}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <input id="opid{{$orderedproduct->id}}" type="hidden" name="opid" value="{{$orderedproduct->id}}">
                                                    <div class="form-group">
                                                      <label for="" style="color:#000;"><strong>Reason</strong></label>
                                                      <textarea id="reason{{$orderedproduct->id}}" class="form-control" name="reason" rows="5" cols="80" placeholder="Please write your reason" required {{!empty($orderedproduct->refund->reason) ? 'readonly' : ''}}>{{!empty($orderedproduct->refund->reason) ? $orderedproduct->refund->reason : ''}}</textarea>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    @if (empty($orderedproduct->refund->reason))
                                                      <button type="button" class="btn base-bg text-white" onclick="refund({{$orderedproduct->id}})">Send Request</button>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                  </div>

                                              </div>
                                            </div>
                                          </div>

                                        @endforeach
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="col-xl-3">
                <div class="card order-summary">
                  <div class="card-header base-bg">
                    <h4 class="white-txt no-margin">Order Summary</h4>
                  </div>
                  <div class="card-body">
                    <ul>
                      <li><span class="left">CART AMOUNT</span> <span class="right">{{$gs->base_curr_symbol}} {{$order->subtotal + $ccharge}}</span></li>
                      @if ($ccharge > 0)
                        <li><span class="left">COUPON (Discount)</span> <span class="right">- {{$gs->base_curr_symbol}} {{round($ccharge, 0)}}</span></li>
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


@endsection


@push('scripts')
  <script>
    function complain(id) {
      var opid = $("#opid" + id).val();
      var comment = $("#comment" + id).val();
      var comment_type = $("#comment_type" + id).val();

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: '{{route('user.complain')}}',
        type: 'POST',
        data: {
          'opid': opid,
          'comment': comment,
          'comment_type': comment_type,
        },
        success: function(data) {
          if (data == "success") {
            window.location = '{{url()->current()}}';
          }
        }
      });
    }

    function refund(id) {
      var opid = $("#opid" + id).val();
      var reason = $("#reason" + id).val();
      console.log(opid);
      console.log(reason);
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: '{{route('user.refund')}}',
        type: 'POST',
        data: {
          'opid': opid,
          'reason': reason,
        },
        success: function(data) {
          if (data == "success") {
            window.location = '{{url()->current()}}';
          }
        }
      });
    }
  </script>
@endpush
