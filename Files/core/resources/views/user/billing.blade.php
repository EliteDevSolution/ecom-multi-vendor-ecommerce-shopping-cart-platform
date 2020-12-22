@extends('layout.profilemaster')

@section('title', 'Billing Address')

@section('headertxt', 'Billing Address')

@section('content')
    <h3>Billing Address</h3>
    <hr>
    <form class="" action="{{route('user.billingupdate')}}" method="post">
      @csrf
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">First Name</label>
                <input type="text" name="billing_first_name" class="form-control" id="" placeholder="Enter first name" value="{{Auth::user()->billing_first_name}}">
                @if ($errors->has('billing_first_name'))
                  <p class="text-danger">{{$errors->first('billing_first_name')}}</p>
                @endif
              </div> 
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Last Name</label>
                <input type="text" name="billing_last_name" class="form-control" id="" placeholder="Enter last name" value="{{Auth::user()->billing_last_name}}">
                @if ($errors->has('billing_last_name'))
                  <p class="text-danger">{{$errors->first('billing_last_name')}}</p>
                @endif
              </div> 
          </div>
      </div>
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Phone Number</label>
                <input type="text" name="billing_phone" class="form-control" id="" placeholder="Enter phone number" value="{{Auth::user()->billing_phone}}">
                @if ($errors->has('billing_phone'))
                  <p class="text-danger">{{$errors->first('billing_phone')}}</p>
                @endif
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Email Address</label>
                <input type="text" name="billing_email" class="form-control" id="" placeholder="Enter last name" value="{{Auth::user()->billing_email}}">
                @if ($errors->has('billing_email'))
                  <p class="text-danger">{{$errors->first('billing_email')}}</p>
                @endif
              </div>              
          </div>
      </div>
      
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Address</label>
                <input type="text" name="billing_address" class="form-control" id="" placeholder="Enter address" value="{{Auth::user()->billing_address}}">
                @if ($errors->has('billing_address'))
                  <p class="text-danger">{{$errors->first('billing_address')}}</p>
                @endif
              </div>              
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Country</label>
                <select class="form-control" name="billing_country">
                  <option value="" selected disabled>Select a country</option>
                  @foreach ($countries as $country)
                    <option value="{{$country}}" {{Auth::user()->billing_country == $country ? 'selected' : ''}}>{{$country}}</option>
                  @endforeach
                </select>
                @if ($errors->has('billing_country'))
                  <p class="text-danger">{{$errors->first('billing_country')}}</p>
                @endif
              </div> 
          </div>
      </div>
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">State</label>
                <input type="text" name="billing_state" class="form-control" id="" placeholder="Enter state" value="{{Auth::user()->billing_state}}">
                @if ($errors->has('billing_state'))
                  <p class="text-danger">{{$errors->first('billing_state')}}</p>
                @endif
              </div>   
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="">City</label>
                <input type="text" name="billing_city" class="form-control" id="" placeholder="Enter city" value="{{Auth::user()->billing_city}}">
                @if ($errors->has('billing_city'))
                  <p class="text-danger">{{$errors->first('billing_city')}}</p>
                @endif
              </div>
          </div>
      </div>      



      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Zip/Postal Code</label>
                <input type="text" name="billing_zip_code" class="form-control" id="" placeholder="Enter zip code" value="{{Auth::user()->billing_zip_code}}">
                @if ($errors->has('billing_zip_code'))
                  <p class="text-danger">{{$errors->first('billing_zip_code')}}</p>
                @endif
              </div>
          </div>
      </div>   






      <div class="form-group text-center">
        <input type="submit" class="btn base-bg white-txt" value="Update">
      </div>
    </form>
    <br>
@endsection
