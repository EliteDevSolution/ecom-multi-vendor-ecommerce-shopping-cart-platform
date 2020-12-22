@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>General Settings</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <div class="tile-body">
                 <form role="form" method="POST" action="{{route('admin.UpdateGenSetting')}}">
                    {{csrf_field()}}
                    <div class="row">
                       <div class="col-md-3">
                          <h6>Website Title</h6>
                          <div class="input-group">
                             <input name="websiteTitle" type="text" class="form-control form-control-lg" value="{{$gs->website_title}}">
                             <div class="input-group-append"><span class="input-group-text">
                                <i class="fa fa-file-text-o"></i>
                                </span>
                             </div>
                          </div>
                          @if ($errors->has('websiteTitle'))
                            <span style="color:red;">{{$errors->first('websiteTitle')}}</span>
                          @endif
                          <span class="text-danger"></span>
                       </div>

                       <div class="col-md-3">
                          <h6>SITE BASE COLOR (WITHOUT #)</h6>
                          <div class="input-group">
                             <input style="background-color:#{{$gs->base_color_code}}" type="text" class="form-control form-control-lg" value="{{$gs->base_color_code}}" name="baseColorCode">
                             <div class="input-group-append"><span class="input-group-text">
                                <i class="fa fa-paint-brush"></i>
                                </span>
                             </div>
                          </div>
                          @if ($errors->has('baseColorCode'))
                            <span style="color:red;">{{$errors->first('baseColorCode')}}</span>
                          @endif
                       </div>
                       <div class="col-md-2">
                          <h6>BASE CURRENCY TEXT</h6>
                          <div class="input-group">
                             <input type="text" class="form-control form-control-lg" value="{{$gs->base_curr_text}}" name="baseCurrencyText">
                             <div class="input-group-append"><span class="input-group-text">
                                <i class="fa fa fa-money"></i>
                                </span>
                             </div>
                          </div>
                          @if ($errors->has('baseCurrencyText'))
                            <span style="color:red;">{{$errors->first('baseCurrencyText')}}</span>
                          @endif
                       </div>
                       <div class="col-md-2">
                          <h6>BASE CURRENCY SYMBOL</h6>
                          <div class="input-group">
                             <input type="text" class="form-control form-control-lg" value="{{$gs->base_curr_symbol}}" name="baseCurrencySymbol">
                             <div class="input-group-append"><span class="input-group-text">
                                <i class="fa fa fa-money"></i>
                                </span>
                             </div>
                          </div>
                          @if ($errors->has('baseCurrencySymbol'))
                            <span style="color:red;">{{$errors->first('baseCurrencySymbol')}}</span>
                          @endif
                       </div>
                       <div class="col-md-2">
                          <h6>Main Hub Location</h6>
                          <div class="input-group">
                             <input type="text" class="form-control form-control-lg" value="{{$gs->main_city}}" name="main_city">
                             <div class="input-group-append"><span class="input-group-text">
                                <i class="fa fa fa-money"></i>
                                </span>
                             </div>
                          </div>
                          @if ($errors->has('baseCurrencySymbol'))
                            <span style="color:red;">{{$errors->first('baseCurrencySymbol')}}</span>
                          @endif
                       </div>
                    </div>
                    <br>
                    <div class="row">

                       <div class="col">
                          <h6>EMAIL VERIFICATION</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="emailVerification" {{$gs->email_verification == 0 ? 'checked' : ''}}>
                       </div>
                       <div class="col">
                          <h6>SMS VERIFICATION</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="smsVerification" {{$gs->sms_verification == 0 ? 'checked' : ''}}>
                       </div>
                       <div class="col">
                          <h6>EMAIL NOTIFICATION</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="emailNotification" {{$gs->email_notification == 1 ? 'checked' : ''}}>
                       </div>

                       <div class="col">
                          <h6>SMS NOTIFICATION</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="smsNotification" {{$gs->sms_notification == 1 ? 'checked' : ''}}>
                       </div>
                       <div class="col">
                          <h6>REGISTRATION</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="registration" {{$gs->registration == 1 ? 'checked' : ''}}>
                       </div>
                    </div>
                    <br>

                    <div class="row">
                       <div class="col">
                          <h6>FACEBOOK LOGIN STATUS</h6>
                          <input data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                             data-width="100%" type="checkbox"
                             name="status" {{$provider->status == 1 ? 'checked' : ''}}>
                       </div>
                       <div class="col">
                         <h6>FACEBOOK APP ID</h6>
                         <input class="form-control form-control-lg" name="app_id" value="{{$provider->client_id}}" type="text">
                         @if ($errors->has('app_id'))
                           <p class="text-danger">{{$errors->first('app_id')}}</p>
                         @endif
                       </div>
                       <div class="col">
                          <h6>FACEBOOK APP SECRET</h6>
                          <input class="form-control form-control-lg" name="app_secret" value="{{$provider->client_secret}}" type="text">
                          @if ($errors->has('app_secret'))
                            <p class="text-danger">{{$errors->first('app_secret')}}</p>
                          @endif
                       </div>
                    </div>
                    <br>
                    <div class="row">
                       <hr>
                       <div class="col-md-12 ">
                          <button type="submit" class="btn btn-primary btn-block btn-lg">UPDATE</button>
                       </div>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
