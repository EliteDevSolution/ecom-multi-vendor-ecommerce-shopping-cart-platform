@extends('layout.master')

@section('title', 'Change Password')

@section('headertxt', 'Change Password')


@section('content')

  <!-- product upload area start -->
  <div class="product-upload-area">
      <div class="container">
          <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="card">
                  <div class="card-header base-bg">
                    <h4 class="mb-0 text-white">Change Password</h4>
                  </div>
                  <div class="card-body">
                    <div class="product-upload-inner"><!-- product upload inner -->
                        <form class="product-upload-form" method="post" action="{{route('vendor.updatePassword')}}">
                            {{csrf_field()}}

                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-element margin-bottom-20">
                                    <label>Old Password <span>**</span></label>
                                    <input name="old_password" type="text" class="input-field" placeholder="Old Password...">
                                    @if ($errors->has('old_password'))
                                        <span style="color:red;">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @else
                                        @if ($errors->first('oldPassMatch'))
                                            <span style="color:red;">
                                                <strong>Old password doesn't match with the existing password</strong>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-element margin-bottom-20">
                                    <label>New Password <span>**</span></label>
                                    <input name="password" type="text" class="input-field" placeholder="New Password">
                                    @if ($errors->has('password'))
                                        <span style="color:red;">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-element margin-bottom-20">
                                    <label>Confirm Password <span>**</span></label>
                                    <input name="password_confirmation" type="text" class="input-field" placeholder="Confirm Password">
                                </div>
                              </div>
                            </div>

                            <div class="btn-wrapper">
                                <input type="submit" class="submit-btn" value="Submit">
                            </div>
                        </form>
                    </div><!-- //.product upload inner -->
                  </div>
                </div>

              </div>
          </div>
      </div>
  </div>
  <!-- product upload area end -->


@endsection
