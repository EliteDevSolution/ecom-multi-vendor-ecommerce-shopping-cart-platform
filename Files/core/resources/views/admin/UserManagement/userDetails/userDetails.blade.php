@extends('admin.layout.master')

@push('styles')
  <style media="screen">
  h2, h3, h4 {
    margin: 0px;
  }
  .widget-small {
    margin-bottom: 0px;
    border: 1px solid #f1f1f1;
  }
  .info h4 {
    font-size: 14px !important;
  }
  </style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>User Details</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="row">
              <div class="col-md-3">
                <div class="card border-primary">
                  <div class="card-header border-primary bg-primary">
                    <h3 style="color:white;"><i class="fa fa-user"></i> PROFILE</h3>
                  </div>
                  <div class="card-body">
                    <div class="text-center">
                      <h3>{{$user->username}}</h3><br>
                      <h4>{{$user->email}}</h4><br>
                      <a href="{{route('admin.emailToUser', $user->id)}}" style="color:white;" class="btn btn-danger btn-block"><i class="fa fa-envelope"></i> SEND MAIL</a>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <div class="card">
                  <div class="card-header bg-primary">
                    <h3 style="color:white;"><i class="fa fa-cog"></i> UPDATE PROFILE</h3>
                  </div>
                  <div class="card-body">
                    <form class="" action="{{route('admin.updateUserDetails')}}" method="post">
                      {{csrf_field()}}
                      <input type="hidden" name="userID" value="{{$user->id}}">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for=""><strong>First Name</strong></label>
                            <input class="form-control" type="text" name="first_name" value="{{$user->first_name}}">
                            @if ($errors->has('first_name'))
                             <p class="text-danger" style="margin:0px;">{{$errors->first('first_name')}}</p>
                           @endif
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for=""><strong>Last Name</strong></label>
                            <input class="form-control" type="text" name="last_name" value="{{$user->last_name}}">
                            @if ($errors->has('last_name'))
                             <p class="text-danger" style="margin:0px;">{{$errors->first('last_name')}}</p>
                           @endif
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for=""><strong>Email</strong></label>
                            <input class="form-control" type="text" name="email" value="{{$user->email}}">
                            @if ($errors->has('email'))
                             <p class="text-danger" style="margin:0px;">{{$errors->first('email')}}</p>
                           @endif
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for=""><strong>Phone</strong></label>
                            <input class="form-control" type="text" name="phone" value="{{$user->phone}}">
                            @if ($errors->has('phone'))
                             <p class="text-danger" style="margin:0px;">{{$errors->first('phone')}}</p>
                           @endif
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                           <label><strong>Status</strong></label>
                           <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                              data-width="100%" type="checkbox" data-on="ACTIVE" data-off="BLOCKED"
                              name="status" {{$user->status=='active'?'checked':''}}>
                        </div>
                        <div class="col-md-4">
                           <label><strong>Email Verification</strong></label>
                           <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                              data-width="100%" type="checkbox" data-on="VERIFIED" data-off="NOT VERIFIED"
                              {{($user->email_verified==1)?'checked':''}} name="emailVerification">
                        </div>
                        <div class="col-md-4">
                           <label><strong>SMS Verification</strong></label>
                           <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                              data-width="100%" type="checkbox" data-on="VERIFIED" data-off="NOT VERIFIED"
                              {{($user->sms_verified==1)?'checked':''}} name="smsVerification">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-12">
                          <button type="submit" class="btn btn-info btn-block" name="button">UPDATE</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
     </div>
  </main>
@endsection
