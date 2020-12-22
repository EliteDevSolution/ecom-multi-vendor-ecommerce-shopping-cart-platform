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
           <h1>Facebook Login</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">
            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="{{route('admin.facebook.update')}}" method="post" role="form">
                   {{csrf_field()}}
                   <div class="form-body">
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">Status</strong></label>
                        <div class="col-md-12">
                           <select class="form-control" name="status">
                             <option value="1" {{$provider->status == 1 ? 'selected' : ''}}>Active</option>
                             <option value="0" {{$provider->status == 0 ? 'selected' : ''}}>Deactive</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">App ID</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="app_id" value="{{$provider->client_id}}" type="text">
                           @if ($errors->has('app_id'))
                             <p class="text-danger">{{$errors->first('app_id')}}</p>
                           @endif
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-12 "><strong style="text-transform: uppercase;">App Secret</strong></label>
                        <div class="col-md-12">
                           <input class="form-control input-lg" name="app_secret" value="{{$provider->client_secret}}" type="text">
                           @if ($errors->has('app_secret'))
                             <p class="text-danger">{{$errors->first('app_secret')}}</p>
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
