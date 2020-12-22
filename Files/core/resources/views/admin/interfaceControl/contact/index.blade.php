@extends('admin.layout.master')

@push('styles')
<style media="screen">
  h3 {
    margin: 0px;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Contact Setting</h1>
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
                <form class="form-horizontal" action="{{route('admin.contact.update')}}" method="post" role="form">
                   {{csrf_field()}}
                   <div class="form-body">
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Phone</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="con_phone" value="{{$gs->con_phone}}" type="text">
                           @if ($errors->has('con_phone'))
                             <p class="text-danger">{{$errors->first('con_phone')}}</p>
                           @endif
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Email</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="con_email" value="{{$gs->con_email}}" type="text">
                           @if ($errors->has('con_email'))
                             <p class="text-danger">{{$errors->first('con_email')}}</p>
                           @endif
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Address</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="con_address" value="{{$gs->con_address}}" type="text">
                           @if ($errors->has('con_address'))
                             <p class="text-danger">{{$errors->first('con_address')}}</p>
                           @endif
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Working Time</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="work_hours" value="{{$gs->work_hours}}" type="text">
                           @if ($errors->has('work_hours'))
                             <p class="text-danger">{{$errors->first('work_hours')}}</p>
                           @endif
                        </div>
                     </div>
                      <div class="form-group">
                         <div class="col-md-12">
                            <button type="submit" class="btn btn-info btn-block btn-lg">UPDATE</button>
                         </div>
                      </div>
                   </div>
                </form>
              </div>

            </div>
          </div>
        </div>
     </div>
  </main>
@endsection
