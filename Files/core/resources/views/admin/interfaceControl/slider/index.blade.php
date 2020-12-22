@extends('admin.layout.master')

@push('styles')
<style media="screen">
  h3, h5 {
    margin: 0px;
  }
  .testimonial img {
    width: 100%;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Slider Setting</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">
            <div class="row">

              <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.slider.store')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                   <div class="form-body">
                     <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Slider Image</strong></label>
                        <div class="col-sm-12"><input type="file" name="slider"></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Title</strong></label>
                        <div class="col-sm-12"><input type="text" name="title" class="form-control input-lg"></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Bold Text</strong></label>
                        <div class="col-sm-12"><input type="text" name="btxt" class="form-control input-lg"></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Small Text</strong></label>
                        <div class="col-sm-12"><input type="text" name="stxt" class="form-control input-lg"></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-12 control-label"><strong>Url</strong></label>
                        <div class="col-sm-12"><input type="text" name="url" class="form-control input-lg"></div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <button type="submit" class="btn btn-primary btn-block">ADD NEW</button>
                        </div>
                     </div>
                   </div>
                </form>
              </div>

            </div>

            <br>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header bg-primary">
                    <h5 style="color:white;display:inline-block;">Sliders</h5>
                  </div>
                  <div class="card-body">
                      @if (count($sliders) == 0)
                        <h3 class="text-center"> NO SLIDER FOUND</h3>
                      @else
                        @foreach ($sliders as $slider)
                          @if ($loop->iteration % 3 == 1)
                          <div class="row"> {{-- .row start --}}
                          @endif
                          <div class="col-md-4">
                            <div class="card testimonial">
                              <div class="card-header bg-primary">
                                <h5 style="color:white">Slider Text</h5>
                              </div>
                              <div class="card-body text-center">
                                <img src="{{asset('assets/user/interfaceControl/sliders/'.$slider->image)}}" alt="">
                                <p>
                                  <strong>Bold Text:</strong> {{$slider->bold_text}}
                                </p>
                                <p>
                                  <strong>Small Text:</strong> {{$slider->small_text}}
                                </p>
                                <p>
                                  <strong>Title:</strong> {{$slider->title}}
                                </p>
                                <p>
                                  <strong>URL:</strong> {{$slider->url}}
                                </p>
                              </div>
                              <div class="card-footer text-center">
                                <form action="{{route('admin.slider.delete')}}" method="POST">
                                   {{csrf_field()}}
                                   <input type="hidden" name="sliderID" value="{{$slider->id}}">
                                   <button style="color:white;" type="submit" class="btn btn-danger btn-block" name="button">
                                     <i class="fa fa-trash"></i>
                                     Delete
                                   </button>
                                 </form>

                              </div>
                            </div>
                          </div>
                          @if ($loop->iteration % 3 == 0)
                          </div> {{-- .row end --}}
                          <br>
                          @endif
                        @endforeach
                      @endif

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
     </div>
  </main>
@endsection
