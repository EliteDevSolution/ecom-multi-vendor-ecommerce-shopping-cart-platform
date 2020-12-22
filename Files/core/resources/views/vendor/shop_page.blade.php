@extends('layout.master')

@section('title', 'Shop Page')

@section('headertxt', 'Shop Page')

@push('styles')
  <style media="screen">
  /* search page css */
  ul.subcategories {
      padding-left: 20px;
      display: none;
  }
  ul.subcategories.open {
    display: block;
  }
  .category-btn {
      display: block;
  }
  .subcategories a {
      display: block;
      cursor: pointer;
  }
  .js-input-from.form-control, .js-input-to.form-control {
      width: 24%;
  }

  .js-input-from.form-control {
      margin-right: 4%;
  }

  .category-content-area .category-compare {
      padding: 20px;
  }
  li.page-item {
      display: inline-block;
  }

  ul.pagination {
      width: 100%;
  }
  .shop-breadcrumb-inner .logo-wrapper {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-right: 25px;
      background-color: #fff;
  }

  .shop-breadcrumb-inner img.shop-logo {
      width: 100%;
      border-radius: 50%;
  }
  .shop-breadcrumb-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
  }
  .left-shop-header {
      display: flex;
      align-items: center;
  }
  .shop-breadcrumb-inner input[type="text"] {
      border: none;
      background-color: transparent;
      border-bottom: 1px solid #fff;
      color: #fff;
      width: 100%;
      padding-right: 30px;
      padding-bottom: 5px;
  }

  .shop-breadcrumb-inner button {
      background-color: transparent;
      border: none;
      outline: none;
      color: #fff;
      /* margin-right: 0px; */
      cursor: pointer;
      /* width: 17%; */
      position: absolute;
      right: -3px;
      top: -5px;
  }

  .right-shop-header {
      width: 250px;
  }

  .shop-breadcrumb-inner form {
      width: 100%;
      position: relative;
  }
  .shop-breadcrumb-inner input[type="text"]::placeholder {
      color: #fff;
  }
  .breadcrumb-area.extra {
      padding-bottom: 30px;
  }
  .breadcrumb-area {
      padding: 30px 0 110px 0;
  }

  </style>
<link rel="stylesheet" href="{{asset('assets/user/css/range-slider.css')}}">
@endpush

@section('content')
  <!-- category content area start -->
  <div class="category-content-area search-page">
      <div class="container">
          <div class="row">
              <div class="col-lg-3">
                  <div class="category-sidebar"><!-- category sidebar -->
                      <div class="category-filter-widget"><!-- category-filter-widget -->
                        <div class="sidebar-header">
                          <h4>Categories</h4>
                        </div>
                          <ul class="cat-list">
                            <li>
                              <a href="{{url('/').'/shop_page'.'/'.$vendorid}}" class="{{!Request::route('category') ? 'base-txt' : '' }}">All Categories </a>
                            </li>
                            @foreach ($categories as $key => $category)
                              <li>
                                <a href="#" class="category-btn {{Request::route('category') == $category->id ? 'base-txt' : '' }}">{{$category->name}} <span class="right"><i class="fa fa-caret-down"></i></span></a>
                                <ul class="subcategories {{Request::route('category') == $category->id ? 'open' : '' }}">
                                  @foreach ($category->subcategories()->where('status', 1)->get() as $key => $subcategory)
                                    <li><a href="{{route('vendor.shoppage', [$vendorid, $category->id, $subcategory->id])}}" class="{{Request::route('subcategory') == $subcategory->id ? 'base-txt' : '' }}">{{$subcategory->name}}</a></li>
                                  @endforeach
                                </ul>
                              </li>
                            @endforeach
                          </ul>
                      </div><!-- //.category-filter-widget -->
                  </div><!-- //. category sidebar -->

                  <div class="category-compare margin-bottom-30">
                     <div class="sidebar-header">
                        <h4>Filters</h4>
                     </div>
                     <div class="">
                        <form class="wrapper" action="{{url('/').'/shop_page'.'/'.$vendorid.'/'.Request::route('category').'/'.Request::route('subcategory')}}">
                          <input type="hidden" name="sort_by" value="{{request()->input('sort_by')}}">
                          <input type="hidden" name="term" value="{{request()->input('term')}}">
                          <p style="margin-bottom: 5px;"><strong>Price</strong></p>
                           <div class="range-slider">
                              <input type="text" class="js-range-slider" value="" />
                           </div>
                           <div class="extra-controls form-inline">
                              <div class="form-group">
                                 <input id="minprice" name="minprice" type="text" class="js-input-from form-control" value="0" />
                                 <input id="maxprice" name="maxprice" type="text" class="js-input-to form-control" value="0" />
                              </div>
                           </div>

                           @if (Request::route('subcategory'))
                             @if (\App\Subcategory::find(Request::route('subcategory'))->attributes != '[]')
                               @php
                                 $attrs = \App\Subcategory::find(Request::route('subcategory'))->attributes;
                                 $arrattr = json_decode($attrs, true);

                                 $attributes = \App\ProductAttribute::whereIn('id', $arrattr['attributes'])->get();
                               @endphp
                               @foreach ($attributes as $attribute)
                                 <p style="margin-bottom: 5px;"><strong>{{$attribute->name}}</strong></p>
                                 @foreach (\App\Option::where('product_attribute_id', $attribute->id)->get() as $key => $option)
                                   <div class="form-check">
                                     <input name="{{$attribute->attrname}}[]" class="form-check-input" type="checkbox" value="{{$option->name}}" id="defaultCheck{{$option->id}}" @if(array_key_exists("$attribute->attrname", $reqattrs)) {{in_array($option->name, $reqattrs["$attribute->attrname"]) ? 'checked' : ''}}  @endif>
                                     <label class="form-check-label" for="defaultCheck{{$option->id}}">
                                       {{$option->name}}
                                     </label>
                                   </div>
                                 @endforeach
                               @endforeach
                             @endif
                          @endif


                           <div class="text-right pt-2">
                             <button type="submit" class="btn btn-sm btn-warning">Apply</button>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="banner-img">
                      {!! show_ad(3) !!}
                  </div>

              </div>
              <div class="col-lg-9">
                  <div class="right-content-area"><!-- right content area -->
                      <div class="top-content"><!-- top content -->
                          {{-- @if (Request::route('category')) --}}
                            <div class="left-conent">
                              @if (Request::route('category'))
                                <span class="cat">{{\App\Category::find(Request::route('category'))->name}} @if(!empty(Request::route('subcategory'))) <i class="fa fa-arrow-right mx-1"></i> {{\App\Subcategory::find(Request::route('subcategory'))->name}} @endif</span>
                              @else
                                <span class="cat">All Categories</span>
                              @endif
                            </div>

                          <div class="right-content">
                              <ul>
                                  <li>
                                      <div class="form-element has-icon">
                                          <form id="sortForm" action="{{url('/').'/shop_page'.'/'.$vendorid.'/'.Request::route('category').'/'.Request::route('subcategory')}}">
                                            <input type="hidden" name="minprice" value="{{request()->input('minprice')}}">
                                            <input type="hidden" name="maxprice" value="{{request()->input('maxprice')}}">
                                            @if (Request::route('subcategory'))
                                              @if (\App\Subcategory::find(Request::route('subcategory'))->attributes != '[]')
                                                @php
                                                  $attrs = \App\Subcategory::find(Request::route('subcategory'))->attributes;
                                                  $arrattr = json_decode($attrs, true);

                                                  $attributes = \App\ProductAttribute::whereIn('id', $arrattr['attributes'])->get();
                                                @endphp
                                                @foreach ($attributes as $attribute)
                                                  @foreach (\App\Option::where('product_attribute_id', $attribute->id)->get() as $key => $option)
                                                    <div class="form-check" style="display: none;">
                                                      <input name="{{$attribute->attrname}}[]" class="form-check-input" type="checkbox" value="{{$option->name}}" id="defaultCheck{{$option->id}}" @if(array_key_exists("$attribute->attrname", $reqattrs)) {{in_array($option->name, $reqattrs["$attribute->attrname"]) ? 'checked' : ''}}  @endif>
                                                      <label class="form-check-label" for="defaultCheck{{$option->id}}">
                                                        {{$option->name}}
                                                      </label>
                                                    </div>
                                                  @endforeach
                                                @endforeach
                                              @endif
                                            @endif
                                            <select name="sort_by" class="selectpicker input-field select" onchange="document.getElementById('sortForm').submit()">
                                                <option value="" selected disabled>sort by</option>
                                                <option value="date_desc" {{$sortby == 'date_desc' ? 'selected' : ''}}>Date: Newest on top</option>
                                                <option value="date_asc" {{$sortby == 'date_asc' ? 'selected' : ''}}>Date: Oldest on top</option>
                                                <option value="price_desc" {{$sortby == 'price_desc' ? 'selected' : ''}}>Price: High to low</option>
                                                <option value="price_asc" {{$sortby == 'price_asc' ? 'selected' : ''}}>Price: Low to high</option>
                                                <option value="sales_desc" {{$sortby == 'sales_desc' ? 'selected' : ''}}>Top Sales</option>
                                                <option value="rate_desc" {{$sortby == 'rate_desc' ? 'selected' : ''}}>Top Rated</option>
                                            </select>
                                          </form>

                                          <div class="the-icon">
                                                  <i class="fas fa-angle-down"></i>
                                          </div>
                                      </div>
                                  </li>
                                  <li class="grid icon ">
                                          <i class="fas fa-th-large"></i>
                                  </li>
                              </ul>
                          </div>
                      </div><!-- //. top content -->
                      <div class="bottom-content"><!-- top content -->
                          <div class="row">
                            @if (count($products) == 0)
                              <div class="col-md-12 text-center">
                                <h4>NO PRODUCT FOUND</h4>
                              </div>
                            @else
                              @foreach ($products as $key => $product)
                                <div class="col-lg-4 col-md-6">
                                  <div class="single-new-collection-item "><!-- single new collections -->
                                      <div class="thumb">
                                          <img src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="new collcetion image">
                                          <div class="hover">
                                              <a href="{{route('user.product.details', [$product->slug, $product->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                                          </div>
                                      </div>
                                      <div class="content">
                                          <span class="category">{{\App\Category::find($product->category_id)->name}}</span>
                                          <a href="{{route('user.product.details', [$product->slug, $product->id])}}"><h4 class="title">{{strlen($product->title) > 25 ? substr($product->title, 0, 25) . '...' : $product->title}}</h4></a>
                                          @if (empty($product->current_price))
                                            <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->price}}</span></div>
                                          @else
                                            <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$product->price}}</del></div>
                                          @endif
                                      </div>
                                  </div><!-- //. single new collections  -->
                                </div>
                              @endforeach

                              @php
                                $parameters = ['term' => $term, 'sort_by'=>request()->input('sort_by'), 'minprice'=>request()->input('minprice'), 'maxprice' => request()->input('maxprice')];
                              @endphp
                              @if (!empty($reqattrs))
                                  @foreach ($reqattrs as $attrkey => $reqattr)
                                    @foreach ($reqattr as $optionkey => $option)
                                      @php
                                        $parameters["$attrkey"][] = $option;
                                      @endphp
                                    @endforeach
                                  @endforeach
                              @endif
                              <div class="col-md-12">
                                <div class="text-center">
                                    {{$products->appends($parameters)->links()}}
                                </div>
                              </div>
                            @endif
                          </div>
                      </div><!-- //.top content -->
                  </div><!-- //. right content area -->
              </div>
          </div>
      </div>
  </div>
  <!-- category content area end -->

@endsection


@section('js-scripts')
  <script>
    $(document).ready(function() {
      $(document).on('click','.category-btn',function(e) {
        e.preventDefault();

        $('.category-btn').removeClass('base-txt');
        $('.category-btn').next('.subcategories').removeClass('open');

        $(this).toggleClass('base-txt');
        $(this).parent().children('.subcategories').toggleClass('open');

      });
    });
  </script>

  {{-- variables for range slider --}}
  <script>
    var minprice = {{$minprice}};
    var maxprice = {{$maxprice}};
    var curr = '{{$gs->base_curr_symbol}}';
  </script>
  <script src="{{asset('assets/user/js/range-slider.js')}}"></script>


@endsection
