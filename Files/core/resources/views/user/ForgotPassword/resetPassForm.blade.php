@extends('layout.master')

@section('title', 'Reset Password')

@section('headertxt', 'Reset Password')

@section('content')
  <!-- Login Section Start -->
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3" style="padding:50px 0px;">
      <div class="card">
        <div class="card-header base-bg">
          <h3 class="text-white mb-0">Reset Password</h3>
        </div>
        <div class="card-body">
          <div class="">
            <form action="{{route('user.resetPassword')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="code" value="{{$code}}">
              <input type="hidden" name="email" value="{{$email}}">

              <div class="form-element margin-bottom-20">
                  <label>New Password <span>**</span></label>
                  <input style="border: 1px solid #ddd;" name="password" type="password" value="" class="input-field" placeholder="New Password....">
                  @if ($errors->has('password'))
                      <span style="color:red;">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
              </div>

              <div class="form-element margin-bottom-20">
                  <label>Password Confirmation <span>**</span></label>
                  <input style="border: 1px solid #ddd;" name="password_confirmation" type="password" value="" class="input-field" placeholder="Enter Password Again....">
                  @if ($errors->has('password_confirmation'))
                      <span style="color:red;">
                          <strong>{{ $errors->first('password_confirmation') }}</strong>
                      </span>
                  @endif
              </div>

              <div class="btn-wrapper text-center">
                  <input type="submit" class="submit-btn" value="Update Password">
              </div>

            </form>
          </div>
        </div>
      </div>

      </div>
  </div>
</div>
@endsection
