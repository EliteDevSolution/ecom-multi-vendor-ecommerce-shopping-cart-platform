
  <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">

  <title>{{$gs->website_title}} - Admin</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- favicon -->
  <link rel="shortcut icon" href="{{asset('assets/user/interfaceControl/logoIcon/icon.jpg')}}" type="image/x-icon">
  <!-- Toastr  -->
  <link rel="stylesheet" href="{{asset('assets/user/css/toastr.min.css')}}">
  <!-- jQUery UI -->
  <link rel="stylesheet" href="{{asset('assets/user/css/jquery-ui.css')}}">
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/main.css')}}">
  <!-- Font-icon css-->
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
  <!-- Font-icon 5 css-->
  <link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome-5.css')}}">

  {{-- Bootstrap Toggle CSS --}}
  <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-toggle.min.css')}}">
  {{-- jquery datetimepicker css --}}
  <link rel="stylesheet" href="{{asset('assets/user/css/jquery.datetimepicker.min.css')}}">
  <script src="{{asset('assets/admin/js/jquery-3.2.1.min.js')}}"></script>
  <script src="{{asset('assets/user/js/vue.js')}}"></script>
  {{-- File input CSS --}}
  <link href="{{ asset('assets/plugins/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
  @stack('styles')
  {{-- NICedit CDN --}}
  @stack('nicedit-scripts')
