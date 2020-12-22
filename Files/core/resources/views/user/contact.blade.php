@extends('layout.master')

@section('title', 'Contact')

@section('headertxt', 'Contact')

@push('styles')
<style media="screen">
.contact-form-area {
  padding: 0px;
}
.contact-form-area .contact_form .form-element .input-field {
    border: 1px solid #eee;
}
.contact-form-area .contact_form .form-element .input-field.textarea {
    min-height: 200px;
}
</style>
@endpush

@section('content')

  <!-- contact infor area start -->
  <div class="contact-info-area">
      <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="inner-section-title">
                  <strong class="subtitle base-txt">Contact us</strong>
                  <h2 class="title">To get update</h2>
              </div>
            </div>
              <div class="col-lg-5 col-md-6">
                  <div class="left-content-area">
                      <ul>
                          <li>
                              <div class="single-contact-info-item"><!-- single contact info item -->
                                  <div class="icon">
                                      <i class="fas fa-phone"></i>
                                  </div>
                                  <div class="content">
                                      <span class="subtitle">Don't Hesitate To Contact Us!</span>
                                      <span class="title">{{$gs->con_phone}}</span>
                                  </div>
                              </div><!-- //.single contact info item -->
                          </li>
                          <li>
                              <div class="single-contact-info-item"><!-- single contact info item -->
                                  <div class="icon">
                                      <i class="fas fa-clock"></i>
                                  </div>
                                  <div class="content">
                                      <span class="subtitle">{{$gs->work_hours}}</span>
                                      <span class="title">Working time</span>
                                  </div>
                              </div><!-- //.single contact info item -->
                          </li>
                          <li>
                              <div class="single-contact-info-item"><!-- single contact info item -->
                                  <div class="icon">
                                      <i class="fas fa-map-marker-alt"></i>
                                  </div>
                                  <div class="content">
                                      <span class="subtitle">{{$gs->con_address}}</span>
                                      <span class="title">Company Headquarters</span>
                                  </div>
                              </div><!-- //.single contact info item -->
                          </li>
                          <li>
                              <div class="single-contact-info-item"><!-- single contact info item -->
                                  <div class="icon">
                                      <i class="fas fa-envelope"></i>
                                  </div>
                                  <div class="content">
                                      <span class="subtitle">Get In Touch With Us</span>
                                      <span class="title">{{$gs->con_email}}</span>
                                  </div>
                              </div><!-- //.single contact info item -->
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="col-lg-7 col-md-6">
                <!-- contact form area start -->
                <section class="contact-form-area">
                    <div class="container">
                        <form action="{{route('user.contactMail')}}" class="contact_form" method="post">
                          {{csrf_field()}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-element margin-bottom-20">
                                        <input type="text" name="name" id="name" class="input-field" placeholder="Enter name" required>
                                        @if ($errors->has('name'))
                                          <p class="text-danger">{{$errors->first('name')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-element margin-bottom-20">
                                        <input type="text" name="email" id="email" class="input-field" placeholder="Enter email address" required>
                                        @if ($errors->has('email'))
                                          <p class="text-danger">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-element margin-bottom-20">
                                      <input type="text" name="subject" id="subject" class="input-field" placeholder="Enter subject" required>
                                      @if ($errors->has('subject'))
                                        <p class="text-danger">{{$errors->first('subject')}}</p>
                                      @endif
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-element textarea margin-bottom-20">
                                        <textarea  id="message" name="message" cols="30" rows="10" placeholder="Enter message" class="input-field textarea" required></textarea>
                                        @if ($errors->has('message'))
                                          <p class="text-danger">{{$errors->first('message')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="btn-wrapper">
                                <button type="submit" class="submit-btn">Submit now</button>
                            </div>
                        </form>
                    </div>
                </section>
                <!-- contact form area end -->
              </div>
          </div>
      </div>
  </div>
  <!-- contact infor area end -->


@endsection
