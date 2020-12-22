@extends('admin.layout.master')


@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> All Vendors Management</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
          @if (count($vendors) == 0)
          <div class="tile">
            <h3 class="tile-title float-left">Vendors List</h3>
            <div class="float-right icon-btn">
               <form method="GET" class="form-inline" action="{{route('admin.allVendorsSearchResult')}}">
                  <input type="text" name="term" class="form-control" placeholder="Search by shop name" value="{{request()->input('term')}}">
                  <button class="btn btn-outline btn-circle  green" type="submit"><i
                     class="fa fa-search"></i></button>
               </form>
            </div>
            <p style="clear:both;margin:0px;"></p>
            <h2 class="text-center">NO VENDOR FOUND</h2>
          </div>
          @else
          <div class="tile">
             <h3 class="tile-title float-left">Vendors List</h3>
             <div class="float-right icon-btn">
                <form method="GET" class="form-inline" action="{{route('admin.allVendorsSearchResult')}}">
                   <input type="text" name="term" class="form-control" placeholder="Search by shop name" value="{{request()->input('term')}}">
                   <button class="btn btn-outline btn-circle  green" type="submit"><i
                      class="fa fa-search"></i></button>
                </form>
             </div>
             <div class="table-responsive">
                <table class="table">
                   <thead>
                      <tr>
                         <th scope="col">Email</th>
                         <th scope="col">Shop Name</th>
                         <th scope="col">Mobile</th>
                         <th scope="col">Balance</th>
                         <th scope="col">Details</th>
                      </tr>
                   </thead>
                   <tbody>
                     @foreach ($vendors as $vendor)
                     <tr>
                        <td data-label="Email">{{$vendor->email}}</td>
                        <td data-label="Username"><a target="_blank" href="{{route('admin.vendorDetails', $vendor->id)}}">{{$vendor->shop_name}}</a></td>
                        <td data-label="Mobile">{{$vendor->phone}}</td>
                        <td data-label="Balance">{{round($vendor->balance, $gs->dec_pt)}} {{$gs->base_curr_text}}</td>
                        <td  data-label="Details">
                           <a href="{{route('admin.vendorDetails', $vendor->id)}}"
                              class="btn btn-outline-primary ">
                           <i class="fa fa-eye"></i> View </a>
                        </td>
                     </tr>
                     @endforeach
                   </tbody>
                </table>
             </div>
             <div class="text-center">
               {{$vendors->appends(['term' => $term])->links()}}
             </div>
          </div>
          @endif
        </div>
     </div>
  </main>
@endsection
