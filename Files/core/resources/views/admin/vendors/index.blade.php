@extends('admin.layout.master')

@push('styles')
  <style media="screen">
    .action-icon {
      font-size: 20px;
    }
  </style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold">
             @if(request()->path() == 'admin/vendors/all')
             All
             @elseif(request()->path() == 'admin/vendors/pending')
             Pending
             @elseif(request()->path() == 'admin/vendors/accepted')
             Accepted
             @elseif(request()->path() == 'admin/vendors/rejected')
             Rejected
             @endif
             Requests
           </h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
              <div class="row mb-4">
                <div class="col-md-3 offset-md-9">
                  <form method="get"
                  action="
                  @if(request()->path() == 'admin/vendors/all')
                  {{route('admin.vendors.all')}}
                  @elseif(request()->path() == 'admin/vendors/pending')
                  {{route('admin.vendors.pending')}}
                  @elseif(request()->path() == 'admin/vendors/accepted')
                  {{route('admin.vendors.accepted')}}
                  @elseif(request()->path() == 'admin/vendors/rejected')
                  {{route('admin.vendors.rejected')}}
                  @endif
                  "
                  >
                    <input class="form-control" type="text" name="term" value="{{$term}}" placeholder="Search by shop name">
                  </form>
                </div>
              </div>
              @if (count($vendors) == 0)
                <h1 class="text-center"> NO DATA FOUND !</h1>
              @else
                <table class="table table-bordered" style="width:100%;">
                  <thead>
                    <tr>
                        <th>Shop Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Request Date</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($vendors as $key => $vendor)
                      <tr>
                          <td class="padding-top-40">{{$vendor->shop_name}}</td>
                          <td>{{$vendor->email}}</td>
                          <td>{{$vendor->phone}}</td>
                          <td class="padding-top-40">{{date('jS F, o', strtotime($vendor->created_at))}}</td>
                          <td>
                            @if ($vendor->approved == 0)
                              <a href="#" style="margin-right:5px" onclick="accept(event, {{$vendor->id}})"><i class="fa fa-check-circle text-success action-icon"></i></a>
                              <a href="#" title="Reject Request" onclick="reject(event, {{$vendor->id}})"><i class="fa fa-times-circle text-danger action-icon"></i></a>
                            @elseif ($vendor->approved == 1)
                              <span class="badge badge-success">Approved</span>
                            @elseif ($vendor->approved == -1)
                              <span class="badge badge-danger">Rejected</span>
                            @endif
                          </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif

               <!-- print pagination -->
               <div class="row">
                 <div class="col-md-12">
                   <div class="text-center">
                      {{$vendors->appends(['term' => $term])->links()}}
                   </div>
                 </div>
               </div>
               <!-- row -->
        </div>
     </div>
   </div>
  </main>

@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    });

    function accept(e, vendorid) {
      e.preventDefault();
      console.log(vendorid);
      var fd = new FormData();
      fd.append('vendorid', vendorid);
      $.ajax({
        url: '{{route('admin.vendors.accept')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          if (data == "success") {
            window.location = '{{url()->full()}}';
          }
        }
      })
    }

    function reject(e, vendorid) {
      e.preventDefault();
      console.log(vendorid);
      var fd = new FormData();
      fd.append('vendorid', vendorid);
      $.ajax({
        url: '{{route('admin.vendors.reject')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          if (data == "success") {
            window.location = '{{url()->full()}}';
          }
        }
      })
    }
  </script>
@endpush
