@extends('layout.master')

@section('title', 'SMS Verification')

@section('headertxt', 'SMS Verification')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 py-5">
        <div class="login-header">
          <h4 style="">A code has been sent to your phone please enter the code to verify your phone number</h4>
        </div>
        <div class="login-form">
          @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
              {{session('error')}}
            </div>
          @endif
          <form action="{{route('user.checkSmsVerification')}}" method="POST">
            {{csrf_field()}}
            <div class="form-element margin-bottom-20">
                <label>Phone </label>
                <input style="border: 1px solid #ddd;" name="phone" type="text" value="{{Auth::user()->phone}}" class="input-field" readonly>
            </div>
            <div class="form-element margin-bottom-20">
              <label>Verification Code <span>**</span></label>
              <input style="border: 1px solid #ddd;" name="sms_ver_code" type="text" value="" class="input-field" placeholder="Enter your verification code...">
              @if ($errors->has('sms_ver_code'))
                  <span style="color:red;">
                      <strong>{{ $errors->first('sms_ver_code') }}</strong>
                  </span>
              @endif
            </div>

            <div class="btn-wrapper text-center">
                <input type="submit" class="submit-btn" value="Submit">
            </div>
          </form>
          <form action="{{route('user.sendVcode')}}" method="POST" class="mt-2 text-center">
              {{csrf_field()}}
                <input type="hidden" name="phone" value="{{Auth::user()->phone}}" placeholder="" class="input-field-square">
                <div>
                  if you didn't get any mail <button class="btn btn-link" type="submit">click here</button> to resend
                </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
