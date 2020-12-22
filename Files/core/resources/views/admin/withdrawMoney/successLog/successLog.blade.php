@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold"> <i class="fa fa-desktop"></i> Withdraw Log</h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
              @if (count($withdraws) == 0)
                <h1 class="text-center"> NO RESULT FOUND !</h1>
              @else
              <table class="table table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>METHOD</th>
                    <th>SHOP NAME</th>
                    <th>AMOUNT</th>
                    <th>CHARGE</th>
                    <th>TIME</th>
                    <th>TRX #</th>
                    <th>STATUS</th>
                    <th>DETAILS</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $i = 0;
                  @endphp
                  @foreach ($withdraws as $withdraw)
                  <tr>
                    <td>{{++$i}}</td>
                    <td>{{$withdraw->withdrawMethod->name}}</td>
                    <td><a target="_blank" href="{{route('admin.vendorDetails', $withdraw->vendor->id)}}">{{$withdraw->vendor->shop_name}}</a></td>
                    <td>{{$withdraw->amount}}  {{$gs->base_curr_text}}</td>
                    <td>{{$withdraw->charge}}  {{$gs->base_curr_text}}</td>
                    <td>{{$withdraw->created_at->format('l jS \\of F Y h:i:s A')}}</td>
                    <td>{{$withdraw->trx}}</td>
                    <td>{{$withdraw->status}}</td>
                    <td>
                      <a target="_blank" class="btn btn-info" href="{{route('withdrawLog.show', $withdraw->id)}}">Details</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @endif

               <!-- print pagination -->
               <div class="row">
                  <div class="text-center">
                     {{$withdraws->links()}}
                  </div>
               </div>
               <!-- row -->
               <!-- END print pagination -->
        </div>
     </div>
   </div>
  </main>

@endsection
