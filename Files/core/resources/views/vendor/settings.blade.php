@extends('layout.master')

@section('title', 'Settings')

@section('headertxt', 'Settings')

@section('content')
  @php
    $vendor = Auth::guard('vendor')->user();
  @endphp
  <!-- product upload area start -->
  <div class="product-upload-area">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header base-bg">
                    <h3 class="mb-0 text-white">Settings</h3>
                  </div>
                  <div class="card-body">
                    <div class="product-upload-inner"><!-- product upload inner -->
                        <form action="{{route('vendor.setting.update')}}" method="post" class="product-upload-form" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-element margin-bottom-20">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                      @if (empty(Auth::guard('vendor')->user()->logo))
                                        <img src="{{asset('assets/user/img/shop-logo/nopic.jpg')}}" alt="" />
                                      @else
                                        <img src="{{asset('assets/user/img/shop-logo/' . Auth::guard('vendor')->user()->logo)}}" alt="" />
                                      @endif

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="width: 250px;"> </div>
                                    <div>
                                        <span class="btn btn-success btn-file">
                                            <span class="fileinput-new"> Choose Logo </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input name="logo" type="file" value="">
                                        </span>
                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        <label style="display:inline-block;" for=""><span>**</span></label>
                                    </div>
                                </div>
                                @if ($errors->has('shop_name'))
                                  <p class="text-danger">{{$errors->first('shop_name')}}</p>
                                @endif
                            </div>
                            <div class="form-element margin-bottom-20">
                                <label>Enter Shop Name <span>**</span></label>
                                <input name="shop_name" value="{{$vendor->shop_name}}" type="text" class="input-field" placeholder="Enter Shop Name...">
                                @if ($errors->has('shop_name'))
                                  <p class="text-danger">{{$errors->first('shop_name')}}</p>
                                @endif
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-element margin-bottom-20">
                                    <label>Email <span>**</span></label>
                                    <input value="{{$vendor->email}}" type="text" class="input-field" placeholder="Product Email Address..." disabled>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-element margin-bottom-20">
                                    <label>Phone <span>**</span></label>
                                    <input name="phone" value="{{$vendor->phone}}" type="text" class="input-field" placeholder="Enter Phone Number...">
                                    @if ($errors->has('phone'))
                                      <p class="text-danger">{{$errors->first('phone')}}</p>
                                    @endif
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-element margin-bottom-20">
                                    <label>Address <span>**</span></label>
                                    <input name="address" value="{{$vendor->address}}" type="text" class="input-field" placeholder="Enter Address...">
                                    @if ($errors->has('address'))
                                      <p class="text-danger">{{$errors->first('address')}}</p>
                                    @endif
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-element margin-bottom-20">
                                    <label>Zip Code <span>**</span></label>
                                    <input name="zip_code" value="{{$vendor->zip_code}}" type="text" class="input-field" placeholder="Enter Zip Code...">
                                    @if ($errors->has('zip_code'))
                                      <p class="text-danger">{{$errors->first('zip_code')}}</p>
                                    @endif
                                </div>
                              </div>
                            </div>
                            <br>
                            <div class="btn-wrapper">
                                    <input type="submit" class="submit-btn" value="Update Settings">
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
