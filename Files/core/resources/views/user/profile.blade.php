@extends('layout.profilemaster')

@section('title', 'Personal Information')

@section('headertxt', 'Personal Information')

@section('content')
    <h3>Personal Informarion</h3>
    <hr>
    <form class="" action="{{route('user.information.update')}}" method="post">
      @csrf
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">First Name</label>
                <input type="text" name="first_name" class="form-control" id="" placeholder="Enter first name" value="{{Auth::user()->first_name}}">
                @if ($errors->has('first_name'))
                  <p class="text-danger">{{$errors->first('first_name')}}</p>
                @endif
              </div> 
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="" placeholder="Enter last name" value="{{Auth::user()->last_name}}">
                @if ($errors->has('last_name'))
                  <p class="text-danger">{{$errors->first('last_name')}}</p>
                @endif
              </div>
          </div>
      </div>
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" id="" placeholder="" value="{{Auth::user()->email}}" readonly>
              </div>              
          </div> 
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Phone Number</label>
                <input name="phone" type="text" class="form-control" id="" placeholder="Enter phone number" value="{{Auth::user()->phone}}">
                @if ($errors->has('phone'))
                  <p class="text-danger">{{$errors->first('phone')}}</p>
                @endif
              </div>              
          </div>


      </div>
      
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" id="" placeholder="Enter date of birth" value="{{Auth::user()->date_of_birth}}">
                @if ($errors->has('date_of_birth'))
                  <p class="text-danger">{{$errors->first('date_of_birth')}}</p>
                @endif
              </div>              
          </div>          

          <div class="col-md-6">
              <div class="form-group">
                <label for="">Gender</label>
                <select class="form-control" name="gender">
                  <option value="" selected disabled>Select Gender</option>
                  <option value="Male" {{Auth::user()->gender == 'Male' ? 'selected' : ''}}>Male</option>
                  <option value="Female" {{Auth::user()->gender == 'Female' ? 'selected' : ''}}>Female</option>
                </select>
                @if ($errors->has('gender'))
                  <p class="text-danger">{{$errors->first('gender')}}</p>
                @endif
              </div>
          </div>
      </div>      



      <div class="form-group text-center">
        <input type="submit" class="btn base-bg white-txt" value="Update Informarion">
      </div>
    </form>
    <br>

@endsection
