@extends('layout.master')

@section('title', 'Cart Management')
@section('headertxt', 'Cart Management')

@section('content')
  <!-- cart contetn area start -->
  <div class="cart-content-area">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="cart-content-inner"><!-- cart content inner -->
                      <div class="top-content"><!-- top content -->
                          <table class="table table-responsive">
                              <thead>
                                  <tr>
                                      <th>Product</th>
                                      <th>Price</th>
                                      <th>Quantity</th>
                                      <th>Total</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @php
                                  if (Auth::check()) {
                                    $sessionid = Auth::user()->id;
                                  } else {
                                    $sessionid = session()->get('browserid');
                                  }
                                  $carts = \App\Cart::where('cart_id', $sessionid)->get();
                                @endphp
                                @foreach ($carts as $cart)
                                  <tr class="trproduct" id="tr{{$cart->id}}">
                                      <td>
                                          <div class="product-details"><!-- product details -->
                                              <div class="close-btn cart-remove-item">
                                                  <i class="fas fa-times" onclick="removeproduct({{$cart->id}})"></i>
                                              </div>
                                              <div class="thumb">
                                                  <img src="{{asset('assets/user/img/products/'.\App\PreviewImage::where('product_id', $cart->product_id)->first()->image)}}" alt="cart image">
                                              </div>
                                              <div class="content">
                                                  <h4 class="title"><a href="{{route('user.product.details', [$cart->product->slug, $cart->product->id])}}">{{strlen($cart->title) > 35 ? substr($cart->title, 0, 35) . '...' : $cart->title}}</a></h4>
                                              </div>
                                          </div><!-- //. product detials -->
                                      </td>
                                      <td>
                                          <div class="price">
                                            @if (empty($cart->current_price))
                                              {{$gs->base_curr_symbol}} {{round($cart->price, 2)}}
                                            @else
                                              {{$gs->base_curr_symbol}} {{round($cart->current_price, 2)}} <del class="dprice ml-2 text-secondary">{{$gs->base_curr_symbol}} {{round($cart->price, 2)}}</del>
                                            @endif
                                          </div>
                                      </td>
                                      <td>
                                          <div class="qty">
                                              <ul>
                                                  <li><span class="qtminus" id="qtminus{{$cart->id}}" onclick="qtchange({{$cart->id}}, 'minus')"><i class="fas fa-minus"></i></span></li>
                                                  <li><span class="qttotal" id="qttotal{{$cart->id}}">{{$cart->quantity}}</span></li>
                                                  <li><span class="qtplus" id="qtplus{{$cart->id}}" onclick="qtchange({{$cart->id}}, 'plus')"><i class="fas fa-plus"></i></span></li>
                                              </ul>
                                          </div>
                                      </td>
                                      <td>
                                          <div class="price total-item-price">
                                            <span id="price{{$cart->id}}">
                                            @if ($cart->current_price)
                                              {{$gs->base_curr_symbol}} {{round($cart->current_price*$cart->quantity, 2)}}
                                            @else
                                              {{$gs->base_curr_symbol}} {{round($cart->price*$cart->quantity, 2)}}
                                            @endif
                                            </span>
                                          </div>
                                      </td>
                                  </tr>
                                @endforeach
                              </tbody>
                          </table>
                      </div><!-- //. top content -->
                      <div class="bottom-content"><!-- bottom content -->
                          <div class="left-content-area">
                              <div class="coupon-code-wrapper">
                                  <a href="{{route('user.search')}}" class="boxed-btn btn" style="width: 200px;"> GO TO SHOPPING </a>
                              </div>
                          </div>
                          <div class="right-content-area">
                              <div class="btn-wrapper">
                                  <button type="button" class="boxed-btn" onclick="updatecart(event);"> Update Cart </button>
                                  <a class="boxed-btn" href="{{route('user.checkout.index')}}"> Proceed to Checkout </a>
                              </div>

                              <div class="cart-total">
                                <br>
                                  <h5>Select a Shipping Method:</h5>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox1" name="place" value="in" onchange="calcTotal(document.getElementById('paymentMethod').value)" {{session('place')=='in'?'checked':''}}>
                                        <label class="form-check-label" for="inlineCheckbox1">In {{$gs->main_city}}</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox2" name="place" value="around" onchange="calcTotal(document.getElementById('paymentMethod').value)" {{session('place')=='around'?'checked':''}}>
                                        <label class="form-check-label" for="inlineCheckbox2">Around {{$gs->main_city}}</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox3" name="place" value="world" onchange="calcTotal(document.getElementById('paymentMethod').value)" {{session('place')=='world' || !session()->has('place')?'checked':''}}>
                                        <label class="form-check-label" for="inlineCheckbox3">Around the World</label>
                                      </div>
                                    </div>
                                  </div>
                                  <br>

                                  <h5>Payment Method:</h5>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <select class="form-control" name="payment_method" id="paymentMethod" onchange="calcTotal(this.value)">
                                        <option value="1" {{session('paymentMethod')==1 || !session()->has('paymentMethod')?'selected':''}}>Cash on delivery</option>
                                        <option value="2" {{session('paymentMethod')==2?'selected':''}}>Advance</option>
                                      </select>
                                    </div>
                                  </div>
                                  <br>
                                  @if (Auth::check())
                                    @php
                                      $countCoupon = \App\CartCoupon::where('cart_id', Auth::user()->id)->count();
                                    @endphp
                                    @if ($countCoupon > 0)
                                      <div class="alert alert-success" role="alert">
                                        Coupon already applied! This coupon code is valid till {{date('jS F, Y', strtotime($gs->valid_till))}}
                                      </div>
                                    @endif
                                  @endif

                                  <h3 class="title">Cart Totals</h3>
                                  <ul class="cart-list">
                                      <li>Subtotal <span class="right" id="subtotal">{{$gs->base_curr_symbol}} {{getSubTotal($sessionid)}}</span></li>
                                      <li>Shipping Charge <span class="right" id="shippingCharge"></span></li>
                                      <li>Tax <span class="right">{{$gs->tax}}%</span></li>
                                      <li class="total">Total <span class="right" id="total"></span></li>
                                  </ul>
                              </div>
                          </div>

                      </div><!-- //. bottom content -->
                  </div><!-- //. cart content inner -->
              </div>
          </div>
      </div>
  </div>
  <!-- cart contetn area end -->

@endsection


@push('scripts')
  <script>
      $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      });

      var removedpros = [];
      var curr = "{{$gs->base_curr_symbol}}";

      function qtchange(id, status) {
        var fd = new FormData();

        var qt = $("#qttotal"+id).text();
        if (status == 'plus') {
          var newqt = parseInt(qt) + 1;
        } else if (status == 'minus' && qt>=2) {
          var newqt = parseInt(qt) - 1;
        } else {
          var newqt = 1;
        }
        $("#qttotal"+id).html(newqt);
        // console.log(newqt);

        // check if vendor has enough products in stock
        fd.append('id', id);
        fd.append('quantity', newqt);
        $.ajax({
          url: '{{route('stock.check')}}',
          type: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function(data) {
            // console.log(data);
            if (data.status == 'shortage') {
              $("#qttotal"+id).html(newqt - 1);
              toastr["error"]("<strong>Sorry!</strong> Vendor has "+ data.quantity +" items left for this product!");
            }
          }
        });
      }

      function updatecart(e) {
        e.preventDefault();
        var fd = new FormData();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.qttotal').each(function() {
          fd.append('qts[]', $(this).text());
        });
        for (var i = 0; i < removedpros.length; i++) {
          fd.append('removedpros[]', removedpros[i]);
        }
        $.ajax({
          url: '{{route('cart.update')}}',
          type: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function(data) {
            console.log(data);
            if (data.status == "success") {
              var $price = $('.total-item-price');
              // Change total price of each item in DOM...
              for (var i = 0; i < $price.length; i++) {
                $price[i].innerText = curr + " " + data.prices[i];
              }
              $("#itemsCountJquery").removeClass('d-none');
              $("#itemsCountJquery").addClass('d-block');
              $("#itemsCountVue").removeClass('d-block');
              $("#itemsCountVue").addClass('d-none');
              $("#itemsCountJquery").html(data.totalItems);
              // Fire toastr
              toastr["success"]("<strong>Success!</strong> Cart updated successfully!");
              // Change total and subtotal in DOM
              $("#subtotal").html(curr + " " + data.subtotal);
              $("#total").html(curr + " " + data.total);
              // sidebar quantity update
              var qttotal = document.getElementsByClassName('qttotal');
              var sidequantity = document.getElementsByClassName('sidequantity');

              for (var i = 0; i < qttotal.length; i++) {
                sidequantity[i].innerHTML = '('+qttotal[i].innerHTML+')';
              }
            }
          }
        });
      }

      function removeproduct(id) {
        console.log(id);
        $("#tr"+id).remove();
        $("#singleitem"+id).remove();
        removedpros.push(id);
        console.log(removedpros);
        var singleproductitem = document.getElementsByClassName('single-product-item');
        if (singleproductitem.length == 0) {
          document.getElementById('noproduct').style.display = 'block';
          document.getElementById('checkoutbtn').style.display = 'none';
        }
      }

      $(document).ready(function() {
        calcTotal(document.getElementById('paymentMethod').value);
      });

      function calcTotal(paymentMethod) {
        var place;
        var shippingmethod = document.getElementsByName('place');
        for (var i = 0; i < shippingmethod.length; i++) {
          if (shippingmethod[i].checked) {
            place = shippingmethod[i].value;
          }
        }
        // console.log(place);
        // console.log(paymentMethod);
        $.get(
          '{{route('cart.getTotal')}}',
          {
            place: place,
            paymentMethod: paymentMethod,
            subtotal: {{getSubTotal($sessionid)}}
          },
          function(data) {
            console.log(data);
            $("#shippingCharge").html(curr + " " + data.shippingcharge);
            $("#total").html(curr + " " + data.total);
          }
        );
      }
  </script>
@endpush
