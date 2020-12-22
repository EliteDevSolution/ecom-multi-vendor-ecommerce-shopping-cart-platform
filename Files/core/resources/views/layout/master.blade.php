<!DOCTYPE html>
<html lang="en">
<head>
  @includeif('layout.partials.head')
</head>

<body>

  <div id="app">
    @includeif('layout.partials.gennavbar')

    @if (request()->path() == '/')
      @yield('headerarea')
    @elseif (request()->is('shop_page/*'))
      <!-- breadcrumb area start -->
      <section class="breadcrumb-area breadcrumb-bg extra shop-page-breadcrumb">
          <div class="container">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="shop-breadcrumb-inner"><!-- breadcrumb inner -->
                        <div class="left-shop-header">
                          <div class="logo-wrapper">
                            <img class="shop-logo" src="{{asset('assets/user/img/shop-logo/'.$vendor->logo)}}" alt="">
                          </div>
                          <div class="shop-name-wrapper">
                            <h3 class="shop-name text-white">{{$vendor->shop_name}}</h3>
                          </div>
                        </div>
                        <div class="right-shop-header">
                          <form method="get" action="{{url('/').'/shop_page'.'/'.$vendorid.'/'.Request::route('category').'/'.Request::route('subcategory')}}">
                            <input type="text" name="term" value="{{$term}}" placeholder="Search this vendor's products">
                            <button type="submit"><i class="fa fa-search"></i></button>
                          </form>
                        </div>
                      </div><!-- //. breadcrumb inner -->
                  </div>
              </div>
          </div>
      </section>
      <!-- breadcrumb area end -->

    @else
      @includeif('layout.partials.genheader')
    @endif

    <div>
      @yield('content')
    </div>

    @if ($gs->home_style == 'home2')
      @includeif('layout.partials.footer2')
    @else
      @includeif('layout.partials.footer')
    @endif

    @includeif('layout.partials.preloader_bt')
  </div>


  @includeif('layout.partials.scripts')

  @stack('scripts')
  @yield('paymentscripts')
</body>
