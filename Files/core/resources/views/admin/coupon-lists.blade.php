@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div class="row" style="width:100%">
          <div class="col-md-6">
            <h1 class="float-left">Coupon Lists</h1>
          </div>
          <div class="col-md-6">
            <a href="{{route('admin.coupon.create')}}" class="btn btn-primary float-right">Add Coupon</a>
          </div>
        </div>
     </div>

     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <div class="tile-body">
                @if (count($coupons) == 0)
                  <h4 class="text-center">NO COUPON FOUND</h4>
                @else
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Minimum Amount</th>
                        <th scope="col">Valid Till</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coupons as $key => $coupon)
                        <tr>
                          <th scope="row">{{$coupon->coupon_code}}</th>
                          <td>{{$coupon->coupon_type}}</td>
                          <td>{{$coupon->coupon_amount}}</td>
                          <td>{{$coupon->coupon_min_amount}}</td>
                          <td>{{$coupon->valid_till}}</td>
                          <td>
                            <a href="{{route('admin.coupon.edit', $coupon->id)}}" class="btn btn-info">Edit</a>
                            <form class="d-inline-block" action="{{route('admin.coupon.delete', $coupon->id)}}" method="post">
                              {{csrf_field()}}
                              <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
                              <input class="btn btn-danger" type="submit" value="Delete">
                            </form>
                          </td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                @endif

              </div>
           </div>
        </div>
     </div>
  </main>
@endsection
