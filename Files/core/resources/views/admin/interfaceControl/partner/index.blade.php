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
           <h1>Partner Setting</h1>
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
                <form action="{{route('admin.partner.store')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                   <div class="form-body">
                      <div class="form-group">
                         <label class="control-label"><strong>Partner Image</strong></label>
                         <div><input type="file" name="partner"></div>
                      </div>
                      <div class="form-group">
                         <label class="control-label"><strong>URL</strong></label>
                         <div><input type="text" name="url" class="form-control input-lg"></div>
                      </div>
                      <div class="row">
                         <div class="col-md-12">
                            <button type="submit" class="btn btn-info btn-block">ADD NEW</button>
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
                    <h5 style="color:white;display:inline-block;">Partners</h5>
                  </div>
                  <div class="card-body">
                      @if (count($partners) == 0)
                        <h3 class="text-center"> NO PARTNER FOUND</h3>
                      @else
                      <div class="row"> {{-- .row start --}}
                        @foreach ($partners as $partner)
                            <div class="col-md-3">
                              <div class="card testimonial">
                                <div class="card-header bg-primary">
                                  <h5 style="color:white">Partner</h5>
                                </div>
                                <div class="card-body text-center">
                                  <img src="{{asset('assets/user/interfaceControl/partners/'.$partner->image)}}" alt="">

                                  <h3 style="margin-top:20px;">URL: {{$partner->url}}</h3>
                                </div>
                                <div class="card-footer text-center">
                                  <form action="{{route('admin.partner.delete')}}" method="POST">
                                     {{csrf_field()}}
                                     <input type="hidden" name="partnerID" value="{{$partner->id}}">
                                     <button style="color:white;" type="submit" class="btn btn-danger btn-block" name="button">
                                       <i class="fa fa-trash"></i>
                                       Delete
                                     </button>
                                   </form>

                                </div>
                              </div>
                            </div>

                        @endforeach
                      </div> {{-- .row end --}}
                      <br>
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
