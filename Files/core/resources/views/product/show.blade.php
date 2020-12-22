@extends('layout.master')

@section('title', "$product->title")

@section('headertxt', 'Product Details')

@push('styles')
  <style media="screen">
  div.ratingpro {
      display: inline-block;
  }
  .product-details-slider.owl-carousel .owl-item img {
    width: auto;
  }
  @media only screen and (max-width: 575px) {
    .product-details-slider.owl-carousel .owl-item img {
      width: 100%;
    }
  }
  </style>
  <link rel="stylesheet" href="{{asset('assets/user/css/comments.css')}}">
<link rel="stylesheet" href="{{asset('assets/user/css/easyzoom.css')}}">
@endpush

@section('content')
  <!-- product details content area  start -->
      <div class="product-details-content-area">
          <div class="container">
              <div class="row">
                  <div class="col-lg-6">
                      <div class="left-content-area"><!-- left content area -->
                          <div class="product-details-slider" id="product-details-slider" data-slider-id="1">
                            @foreach($product->previewimages as $previewimage)
                              <div class="single-product-thumb">
                              <div class="easyzoom easyzoom--overlay">
                        				<a href="{{asset('assets/user/img/products/'.$previewimage->big_image)}}">
                        					<img class="single-image" src="{{asset('assets/user/img/products/'.$previewimage->image)}}" alt=""/>
                        				</a>
                        			</div>
                              </div>
                            @endforeach
                          </div>
                          <ul class="owl-thumbs product-deails-thumb" data-slider-id="1">
                              @foreach($product->previewimages as $previewimage)
                                <li class="owl-thumb-item">
                                    <img src="{{asset('assets/user/img/products/'.$previewimage->image)}}" alt="product details thumb">
                                </li>
                              @endforeach
                          </ul>


                      </div><!-- //.left content area -->
                  </div>

                  <div class="col-lg-6">
                      <div class="right-content-area"><!-- right content area -->
                          <div class="top-content">
                              <ul class="review">
                                  <div class="ratingpro" id="ratingPro{{$product->id}}"></div>
                                  @if (\App\ProductReview::where('product_id', $product->id)->count() == 0)
                                    <li class="reviewes"><span class="badge badge-danger">No Reviews</span> </li>
                                  @else
                                    <li class="reviewes">({{\App\ProductReview::where('product_id', $product->id)->count()}} <small>reviews</small>)</li>
                                  @endif
                              </ul>
                              <span class="orders">Orders ({{$sales}})</span>
                          </div>
                          <form class="bottom-content" id="attrform" enctype="multipart/form-data">
                            {{csrf_field()}}
                              <span class="cat">{{$product->category->name}}</span>
                              <h3 class="title">
                                {{$product->title}}
                                @if ($product->deleted == 1)
                                  <span class="badge badge-danger">Removed</span>
                                @endif
                              </h3>
                              <div class="price-area">
                                  <div class="left">
                                      @if (empty($product->current_price))
                                        <span class="sprice">{{$gs->base_curr_symbol}} {{round($product->price, 2)}}</span>
                                      @else
                                        <span class="sprice">{{$gs->base_curr_symbol}} {{round($product->current_price, 2)}}</span>
                                        <span class="dprice"><del>{{$gs->base_curr_symbol}} {{round($product->price, 2)}}</del></span>
                                      @endif
                                  </div>
                              </div>

                              <ul class="product-spec">
                                  <li>Subcategory:  <span class="right">{{$product->subcategory->name}} </span></li>
                                  <li>Product Code: <span class="right">{{$product->product_code}}</span></li>
                                  @if ($product->quantity > 0)
                                    <li>Stock:  <span class="right base-color" id="stock">In Stock </span></li>
                                  @else
                                    <li>Stock:  <span class="right text-danger" id="stock">Out of stock </span></li>
                                  @endif

                                  @php
                                    $attrs = json_decode($product->attributes, true);
                                  @endphp

                                  @foreach ($attrs as $key => $attr)
                                    @php
                                      $pieces = explode("_", $key);
                                      foreach ($pieces as &$piece)
                                      {
                                          $piece = ucfirst($piece);
                                      }
                                      $wrapped_lines = join(' ', $pieces);
                                    @endphp
                                    <li>

                                      <strong class="mb-2">{{$wrapped_lines}}</strong>:<br>
                                      @foreach ($attr as $value)
                                        <span class="mr-2"><input type="radio" name="{{$key}}[]" value="{{$value}}"> {{$value}}</span>
                                      @endforeach
                                    </li>
                                  @endforeach
                                  <li>Shop Name:  <span class="right"><a href="{{route('vendor.shoppage', $product->vendor->id)}}" style="color:#{{$gs->base_color_code}};font-weight:700;">{{$product->vendor->shop_name}}</a> </span></li>
                                  <p class="text-danger" id="errattr"></p>
                              </ul>


                              <div class="paction">
                                  <div class="qty">
                                      <ul>
                                          <li><span class="qtminus" id="qtminus" onclick="qtchange('minus')"><i class="fas fa-minus"></i></span></li>
                                          <li><span class="qttotal" ref="qttotal" id="qttotal">1</span></li>
                                          <li><span class="qtplus" id="qtplus" onclick="qtchange('plus')"><i class="fas fa-plus"></i></span></li>
                                      </ul>
                                  </div>
                                  <ul class="activities">
                                    @if (Auth::check())
                                      @php
                                        $count = \App\Favorit::where('user_id', Auth::user()->id)->where('product_id', $product->id)->count();
                                      @endphp
                                      @if ($count == 0)
                                        <li><a href="#" onclick="favorit(event, {{$product->id}})"><i id="heart" class="fas fa-heart"></i></a></li>
                                      @else
                                        <li><a href="#" onclick="favorit(event, {{$product->id}})"><i id="heart" class="fas fa-heart red"></i></a></li>
                                      @endif
                                    @else
                                      <li><a href="{{route('login')}}"><i id="heart" class="fas fa-heart"></i></a></li>
                                    @endif

                                      <li><a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a></li>
                                      <li><a href="https://twitter.com/intent/tweet?text=my share text&amp;url={{urlencode(url()->current()) }}"><i class="fab fa-twitter"></i></a></li>
                                      <li><a href="https://plus.google.com/share?url={{urlencode(url()->current()) }}"><i class="fab fa-google-plus-g"></i></a></li>
                                  </ul>
                                  @if (!Auth::guard('vendor')->check())
                                    <div class="btn-wrapper">
                                        <a href="#" class="boxed-btn" @click.prevent="addtocart({{$product->id}}, $refs.qttotal.innerHTML)">add to cart</a>
                                    </div>
                                  @endif

                              </div>
                          </form>
                      </div><!-- //. right content area -->
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                      <div class="product-details-area">
                          <div class="product-details-tab-nav">
                              <ul class="nav nav-tabs" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link {{session('success')=='Reviewed successfully'?'':'active'}}" id="descr-tab" data-toggle="tab" href="#descr" role="tab" aria-controls="descr" aria-selected="false">descriptions</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link {{session('success')=='Reviewed successfully'?'active':''}}" id="item-review-tab" data-toggle="tab" href="#item_review" role="tab" aria-controls="item_review" aria-selected="true">item review</a>
                                  </li>
                                  @auth
                                    @if (\App\ProductReview::where('user_id', Auth::user()->id)->where('product_id', $product->id)->count() == 0)
                                      @if (\App\Orderedproduct::where('user_id', Auth::user()->id)->where('product_id', $product->id)->where('shipping_status', 2)->count() > 0)
                                        <li class="nav-item">
                                          <a class="nav-link" id="write-tab" data-toggle="tab" href="#write" role="tab" aria-controls="write" aria-selected="false">Write Reviews</a>
                                        </li>
                                      @endif
                                    @endif
                                  @endauth
                                  <li class="nav-item">
                                    <a class="nav-link" id="item-review-tab" data-toggle="tab" href="#vendor_info" role="tab" aria-controls="item_review" aria-selected="true">Vendor Information</a>
                                  </li>
                              </ul>
                          </div>
                            <div class="tab-content" >
                              <div class="tab-pane fade {{session('success')=='Reviewed successfully'?'':'show active'}}" id="descr" role="tabpanel" aria-labelledby="descr-tab">
                                  <div class="descr-tab-content">
                                      @includeif('product.partials.description')
                                  </div>
                              </div>
                              <div class="tab-pane fade {{session('success')=='Reviewed successfully'?'show active':''}}" id="item_review" role="tabpanel" aria-labelledby="item-review-tab">
                                <div class="descr-tab-content">
                                    @includeif('product.partials.comments')
                                </div>
                              </div>
                              @auth
                                @if (\App\ProductReview::where('user_id', Auth::user()->id)->where('product_id', $product->id)->count() == 0)
                                  @if (\App\Orderedproduct::where('user_id', Auth::user()->id)->where('product_id', $product->id)->where('shipping_status', 2)->count() > 0)
                                    <div class="tab-pane fade" id="write" role="tabpanel" aria-labelledby="write-tab">
                                        <div class="more-feature-content">
                                          @includeif('product.partials.writereview')
                                        </div>
                                    </div>
                                  @endif
                                @endif
                              @endauth
                              <div class="tab-pane fade" id="vendor_info" role="tabpanel" aria-labelledby="item-review-tab">
                                <div class="descr-tab-content">
                                    @includeif('product.partials.vendor_info')
                                </div>
                              </div>
                            </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  <!-- product details content area end -->

  <!-- recently added start -->
  <div class="recently-added-area product-details">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="recently-added-nav-menu"><!-- recently added nav menu -->
                      <ul>
                          <li>Related Products</li>
                      </ul>
                  </div><!-- //.recently added nav menu -->
              </div>
              <div class="col-lg-12">
                  <div class="recently-added-carousel" id="recently-added-carousel"><!-- recently added carousel -->
                    @foreach ($rproducts as $key => $rproduct)
                      <div class="single-new-collection-item "><!-- single new collections -->
                          <div class="thumb">
                              <img src="{{asset('assets/user/img/products/'.$rproduct->previewimages()->first()->image)}}" alt="new collcetion image">
                              <div class="hover">
                                <a href="{{route('user.product.details', [$rproduct->slug, $rproduct->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                              </div>
                          </div>
                          <div class="content">
                              <span class="category">{{\App\Category::find($rproduct->category_id)->name}}</span>
                              <a href="{{route('user.product.details', [$rproduct->slug, $rproduct->id])}}"><h4 class="title">{{strlen($rproduct->title) > 25 ? substr($rproduct->title, 0, 25) . '...' : $rproduct->title}}</h4></a>
                              @if (empty($rproduct->current_price))
                                <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$rproduct->price}}</span></div>
                              @else
                                <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$rproduct->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$rproduct->price}}</del></div>
                              @endif
                          </div>
                      </div><!-- //. single new collections  -->
                    @endforeach
                    <div class="single-new-collection-item ">
                      <div class="view-all-wrapper">
                        <div class="view-all-inner">
                          <a class="view-all-icon-wrapper" href="{{route('user.search', [$rproduct->category_id, $rproduct->subcategory_id])}}">
                            <i class="fa fa-angle-right"></i>
                          </a>
                          <a class="d-block view-all-txt" href="{{route('user.search', [$rproduct->category_id, $rproduct->subcategory_id])}}">View All</a>
                        </div>
                      </div>
                    </div>
                  </div><!-- //. recently added carousel -->
              </div>
          </div>
      </div>
  </div>
  <!-- recently added end -->


@endsection

@push('scripts')
  <script src="{{asset('assets/user/js/easyzoom.js')}}"></script>
  <script>
  $(document).ready(function() {
    var $easyzoom = $('.easyzoom').easyZoom();
  });

  </script>
  <script>
    function favorit(e, productid) {
      e.preventDefault();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var fd = new FormData();
      fd.append('productid', productid);
      $.ajax({
        url: '{{route('user.favorit')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          if (data == "favorit") {
            toastr["success"]("Added to wishlist!");
            $("#heart").addClass('red');
          } else if (data == "unfavorit") {
            $("#heart").removeClass('red');
          }
        }
      })
    }

    function qtchange(status) {
      var qt = $("#qttotal").text();
      if (status == 'plus') {
        var newqt = parseInt(qt) + 1;
      } else if (status == 'minus' && qt>=2) {
        var newqt = parseInt(qt) - 1;
      } else {
        var newqt = 1;
      }
      $("#qttotal").html(newqt);
      console.log(newqt);
    }
  </script>

  <script>
    var globalrating;

    $(function () {
      $("#writeRateYo").rateYo({
        onChange: function (rating, rateYoInstance) {
          $(this).next().text(rating);
          globalrating = rating;
          console.log(rating);
        },
      });
    });

    function reviewsubmit(e) {
      e.preventDefault();
      // console.log(globalrating);
      var form = document.getElementById('reviewform');
      var fd = new FormData(form);
      if (globalrating) {
        fd.append('rating', globalrating);
      } else {
        fd.append('rating', '');
      }

      $.ajax({
        url: '{{route('user.review.submit')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: (data) => {
          console.log(data);

          if(typeof data.error != 'undefined') {
              if (typeof data.rating != 'undefined') {
                document.getElementById('errrating').innerHTML = data.rating[0];
              }
          } else {
            window.location = '{{url()->current()}}';
          }
        }
      });
    }

    $(document).ready(function() {
      var pid = {{$product->id}};
      $.get("{{route('user.avgrating', $product->id)}}", function(data){
        $("#ratingPro"+pid).rateYo({
          rating: data,
          readOnly: true,
          starWidth: "16px"
        });
      });
    });
  </script>

@endpush
