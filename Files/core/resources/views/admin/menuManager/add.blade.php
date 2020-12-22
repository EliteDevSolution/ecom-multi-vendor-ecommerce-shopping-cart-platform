@extends('admin.layout.master')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('body');
    });
  </script>
@endpush

@push('styles')
<style media="screen">
  h3, h4 {
    margin: 0px;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div class="row" style="width:100%;">
          <div class="col-md-6">
            <h1 class="d-inline-block">Menu Management</h1>
          </div>
          <div class="col-md-6 text-right">
            <a href="{{route('admin.menuManager.index')}}" class="float-right btn btn-primary">Menu Lists</a>
          </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">
            <div class="row">

              <div class="col-md-12">
                <form action="{{route('admin.menuManager.store')}}" method="post" role="form">
                   {{ csrf_field() }}
                   <div class="form-body">
                     <div class="col-md-12">
                       <div class="form-group">
                         <label for="title">Menu Name:</label>
                         <input name="name" type="text" class="form-control" id="title" value="{{old('name')}}">
                         @if ($errors->has('name'))
                           <p style="color:red;">{{$errors->first('name')}}</p>
                         @endif
                       </div>
                     </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="title">Title:</label>
                          <input name="title" type="text" class="form-control" id="title" value="{{old('title')}}">
                          @if ($errors->has('title'))
                            <p style="color:red;">{{$errors->first('title')}}</p>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-12">
                         <div class="form-group">
                           <label for="body">Body:</label>
                           <textarea name="body" class="form-control" rows="8" id="body">{{old('body')}}</textarea>
                           @if ($errors->has('body'))
                             <p style="color:red;">{{$errors->first('body')}}</p>
                           @endif
                         </div>
                      </div>
                      <div class="row">
                         <div class="col-md-12">
                           <div class="col-md-12">
                             <button type="submit" class="btn btn-info btn-block btn-lg">ADD MENU</button>
                           </div>
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
