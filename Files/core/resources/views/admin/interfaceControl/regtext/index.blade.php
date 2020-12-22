@extends('admin.layout.master')

@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('user');
    });
  </script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('vendor');
    });
  </script>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Register Page Text</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <div class="tile-body">
                 <form role="form" method="POST" action="{{route('admin.registertext.update')}}" enctype="multipart/form-data">
                    <div class="form-body">
                       {{csrf_field()}}
                       <div class="form-group">
                          <label><strong>User Register Text</strong></label>
                          <textarea class="form-control" name="user_register_text" id="user" rows="10">{{$gs->user_register_text}}</textarea>
                          @if ($errors->has('user_register_text'))
                            <span style="color:red;">{{$errors->first('user_register_text')}}</span>
                          @endif
                       </div>
                       <div class="form-group">
                          <label><strong>Vendor Register Text</strong></label>
                          <textarea class="form-control" name="vendor_register_text" id="vendor" rows="10">{{$gs->vendor_register_text}}</textarea>
                          @if ($errors->has('vendor_register_text'))
                            <span style="color:red;">{{$errors->first('vendor_register_text')}}</span>
                          @endif
                       </div>
                    </div>
                    <div class="form-actions">
                       <button type="submit" class="btn btn-primary btn-block btn-lg">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
