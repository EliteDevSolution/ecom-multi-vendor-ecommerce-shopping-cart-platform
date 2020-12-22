@extends('layout.master')

@section('title', 'Password Reset Email')

@section('headertxt', 'Email Form')


@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 email-form">
        <div class="card">
          <div class="card-header base-bg">
            <h3 class="text-white mb-0">Email Form</h3>
          </div>
          <div class="card-body">
            @if (session()->has('email_not_available'))
              <div class="alert alert-danger">
                {{session('email_not_available')}}
              </div>
            @endif
            @if (session()->has('message'))
              <div class="alert alert-success">
                {{session('message')}}
              </div>
            @endif

            <form id="sendResetPassMailForm" action="{{route('user.sendResetPassMail')}}" class="" method="post">
              {{csrf_field()}}
              <div class="form-element square login">
                  <div class="form-element margin-bottom-20">
                      <label>Email <span>**</span></label>
                      <input style="border: 1px solid #ddd;" name="resetEmail" type="email" class="input-field" placeholder="Enter your mail address...">
                  </div>
                  @if ($errors->has('resetEmail'))
                    <p class="text-danger">{{$errors->first('resetEmail')}}</p>
                  @endif
              </div>
              <div class="btn-wrapper text-center">
                      <input type="submit" class="submit-btn" value="Send Mail">
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
