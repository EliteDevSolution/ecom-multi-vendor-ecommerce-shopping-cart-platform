@extends('layout.profilemaster')

@section('title', 'Shipping Address')

@section('headertxt', 'Shipping Address')

@section('content')
    <h3>Shipping Address</h3>
    <hr>
    <form class="" action="{{route('user.shippingupdate')}}" method="post">
      @csrf
      
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">First Name</label>
            <input type="text" name="shipping_first_name" class="form-control" id="" placeholder="Enter first name" value="{{Auth::user()->shipping_first_name}}">
            @if ($errors->has('shipping_first_name'))
              <p class="text-danger">{{$errors->first('shipping_first_name')}}</p>
            @endif
          </div>            
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Last Name</label>
            <input type="text" name="shipping_last_name" class="form-control" id="" placeholder="Enter last name" value="{{Auth::user()->shipping_last_name}}">
            @if ($errors->has('shipping_last_name'))
              <p class="text-danger">{{$errors->first('shipping_last_name')}}</p>
            @endif
          </div>            
        </div>    
      </div>


      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Phone Number</label>
            <input type="text" name="shipping_phone" class="form-control" id="" placeholder="Enter phone number" value="{{Auth::user()->shipping_phone}}">
            @if ($errors->has('shipping_phone'))
              <p class="text-danger">{{$errors->first('shipping_phone')}}</p>
            @endif
          </div>  
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Email Address</label>
            <input type="text" name="shipping_email" class="form-control" id="" placeholder="Enter last name" value="{{Auth::user()->shipping_email}}">
            @if ($errors->has('shipping_email'))
              <p class="text-danger">{{$errors->first('shipping_email')}}</p>
            @endif
          </div>            
        </div>    
      </div>
      
      
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Address</label>
            <input type="text" name="address" class="form-control" id="" placeholder="Enter address" value="{{Auth::user()->address}}">
            @if ($errors->has('address'))
              <p class="text-danger">{{$errors->first('address')}}</p>
            @endif
          </div>            
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Country</label>
            <select class="form-control" name="country">
              <option value="" selected disabled>Select a country</option>
              @foreach ($countries as $country)
                <option value="{{$country}}" {{Auth::user()->country == $country ? 'selected' : ''}}>{{$country}}</option>
              @endforeach
            </select>
            @if ($errors->has('country'))
              <p class="text-danger">{{$errors->first('country')}}</p>
            @endif
          </div>            
        </div>    
      </div>
      
      
      
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">State</label>
            <input type="text" name="state" class="form-control" id="" placeholder="Enter state" value="{{Auth::user()->state}}">
            @if ($errors->has('state'))
              <p class="text-danger">{{$errors->first('state')}}</p>
            @endif
          </div>            
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">City</label>
            <input type="text" name="city" class="form-control" id="" placeholder="Enter city" value="{{Auth::user()->city}}">
            @if ($errors->has('city'))
              <p class="text-danger">{{$errors->first('city')}}</p>
            @endif
          </div>  
        </div>    
      </div>
      
      
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Zip/Postal Code</label>
            <input type="text" name="zip_code" class="form-control" id="" placeholder="Enter zip code" value="{{Auth::user()->zip_code}}">
            @if ($errors->has('zip_code'))
              <p class="text-danger">{{$errors->first('zip_code')}}</p>
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
