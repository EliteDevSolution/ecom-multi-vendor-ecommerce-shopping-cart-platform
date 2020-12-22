<!DOCTYPE html>
<html lang="en">
<head>
  @includeif('layout.partials.head')
  @stack('styles')

</head>

<body>
    <div id="app">
      @includeif('layout.partials.gennavbar')

      @includeif('layout.partials.genheader')

      <div class="container">
        <div class="row py-5">
          <div class="col-md-3">
            <div class="card" style="width: 100%;">
              <div class="user-sidebar">
                <div class="card-header base-bg">
                  <h4 class="white-txt no-margin">My Account</h4>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='profile') active @endif" href="{{route('user.profile')}}">Profile</a></li>
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='wishlist') active @endif" href="{{route('user.wishlist')}}">Wishlist</a></li>
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='orders') active @endif" href="{{route('user.orders')}}">Orders</a></li>
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='shipping') active @endif" href="{{route('user.shipping')}}">Shipping Address</a></li>
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='billing') active @endif" href="{{route('user.billing')}}">Billing Address</a></li>
                  <li class="list-group-item"><a class="sidebar-links @if(request()->path()=='changepassword') active @endif" href="{{route('user.changepassword')}}">Change Password</a></li>
                  <li class="list-group-item"><a class="sidebar-links" href="{{route('user.logout')}}">Logout</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            @yield('content')
          </div>
        </div>
      </div>
    </div>

    @includeif('layout.partials.footer')

    @includeif('layout.partials.preloader_bt')

    @includeif('layout.partials.scripts')

    @stack('scripts')

</body>
