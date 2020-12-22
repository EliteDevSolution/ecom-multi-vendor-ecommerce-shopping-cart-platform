@extends('admin.layout.master')


@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold">
             @if(request()->path() == 'admin/flashsale/all')
                 All
             @elseif(request()->path() == 'admin/flashsale/pending')
                 Pending
             @elseif(request()->path() == 'admin/flashsale/accepted')
                 Accepted
             @elseif(request()->path() == 'admin/flashsale/rejected')
                 Rejected
             @endif Requests
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

              @if (count($products) == 0)
                <h1 class="text-center"> NO PRODUCT FOUND !</h1>
              @else
                <table class="table table-bordered" style="width:100%;">
                  <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Vendor</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($products as $key => $product)
                      <tr>
                          <td class="padding-top-40"><a href="{{route('user.product.details', [$product->slug, $product->id])}}">{{strlen($product->title) > 40 ? substr($product->title, 0, 40) . '...' : $product->title}}</a></td>
                          <td class="padding-top-40"><a href="{{route('admin.vendorDetails', $product->vendor->id)}}">{{$product->vendor->shop_name}}</a></td>
                          <td class="padding-top-40">{{$product->vendor->email}}</td>
                          <td class="padding-top-40">{{$product->vendor->phone}}</td>
                          <td class="padding-top-40">{{$product->flash_type == 1 ? 'Percentage' : 'Fixed'}}</td>
                          <td class="padding-top-40">{{$product->flash_amount}} {{$product->flash_type == 1 ? '%' : "$gs->base_curr_text"}}</td>
                          <td class="padding-top-40">{{$product->flash_date}}</td>
                          <td class="padding-top-40">{{\App\FlashInterval::find($product->flash_interval)->start_time ." - ". \App\FlashInterval::find($product->flash_interval)->end_time}}</td>
                          <td class="padding-top-40">
                            @if ($product->flash_status == 0)
                              <strong class="badge badge-warning">Pending</strong>
                            @elseif ($product->flash_status == 1)
                              <strong class="badge badge-success">Accepted</strong>
                            @else
                              <strong class="badge badge-danger">Rejected</strong>
                            @endif
                          </td>
                          <td class="padding-top-40">
                            @if ($product->flash_status == 0)
                              <a href="#" onclick="changeStatus(event, -1, {{$product->id}})"><i class="fa fa-times text-danger"></i></a>
                              <a href="#" onclick="changeStatus(event, 1, {{$product->id}})"><i class="fa fa-check text-success"></i></a>
                            @elseif ($product->flash_status == 1)
                              <a href="#" onclick="changeStatus(event, -1, {{$product->id}})"><i class="fa fa-times text-danger"></i></a>
                            @else
                              <a href="#" onclick="changeStatus(event, 1, {{$product->id}})"><i class="fa fa-check text-success"></i></a>
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
                      {{$products->appends(['term' => $term])->links()}}
                   </div>
                 </div>
               </div>
               <!-- row -->
        </div>
     </div>
   </div>
  </main>

@endsection


@section('js-scripts')
  <script>
    function changeStatus(e, status, id) {
      e.preventDefault();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: '{{route('admin.flashsale.changestatus')}}',
        type: 'POST',
        data: {
          id: id,
          status: status
        },
        success: function(data) {
          console.log(data);
          if (data == "success") {
            window.location = '{{url()->full()}}';
          }
        }
      })
    }
  </script>
@endsection
