@extends('layout.master')

@section('title', 'Coupon Log')

@section('headertxt', 'Coupon Log')

@push('styles')
{{-- jquery datetimepicker css --}}
<link rel="stylesheet" href="{{asset('assets/user/css/jquery.datetimepicker.min.css')}}">
<style media="screen">
li.page-item {
  display: inline-block;
}
</style>
@endpush

@section('content')
  <!-- sellers product content area start -->
  <div class="sellers-product-content-area">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="seller-product-wrapper">
                      <div class="seller-panel">
                          <div class="card-header">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <h3 class="text-white mb-0">Coupon Log</h3>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <form method="get" action="{{url()->current()}}">
                                            <div class="row">
                                              <div class="col-md-3">
                                                <input class="form-control" type="text" name="order_id" value="{{request()->input('order_id')}}" placeholder="Order ID">
                                              </div>
                                              <div class="col-md-3">
                                                <input class="form-control" id="from_date" type="text" name="from_date" value="{{request()->input('from_date')}}" placeholder="From" autocomplete="off">
                                              </div>
                                              <div class="col-md-3">
                                                <input class="form-control" id="to_date" type="text" name="to_date" value="{{request()->input('to_date')}}" placeholder="To" autocomplete="off">
                                              </div>
                                              <div class="col-md-3">
                                                <input class="btn btn-dark btn-block" type="submit" name="" value="Submit">
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                          </div>
                          <div class="sellers-product-inner">
                              <div class="bottom-content">
                                  <table class="table table-default" id="datatableOne">
                                      <thead>
                                          <tr>
                                            <th>Serial</th>
                                            <th>Order ID</th>
                                            <th>Product Title</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Order Date</th>
                                            <th>Coupon Amount</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @foreach ($logs as $log)
                                          <tr>
                                            <td class="padding-top-40">{{$loop->iteration}}</td>
                                            <td class="padding-top-40">{{!empty($log->order) ? $log->order->unique_id : ''}}</td>
                                            <td class="padding-top-40"><a href="{{route('user.product.details', [$log->product->slug,$log->product->id])}}" target="_blank">{{strlen($log->product_name) > 20 ? substr($log->product_name, 0, 20) . '...' : $log->product_name}}</a></td>
                                            <td class="padding-top-40">
                                              @if ($log->offered_product_price)
                                                <del>{{$gs->base_curr_symbol}} {{$log->product_price}}</del> <span>{{$gs->base_curr_symbol}} {{$log->offered_product_price}}</span>
                                              @else
                                                <span>{{$gs->base_curr_symbol}} {{$log->product_price}}</span>
                                              @endif
                                            </td>
                                            <td class="padding-top-40">{{$log->quantity}}</td>
                                            <td class="padding-top-40">{{date('jS M, Y', strtotime($log->created_at))}}</td>
                                            <td class="padding-top-40">{{$gs->base_curr_symbol}} {{$log->coupon_amount}}</td>
                                          </tr>

                                        @endforeach
                                        <tr>
                                          <td colspan="6" class="padding-top-40 text-right"></td>
                                          <td class="padding-top-40 text-left"><strong>Total:</strong> {{$gs->base_curr_symbol}} {{$ctotal}}</td>
                                        </tr>
                                      </tbody>
                                  </table>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="text-center">
                                     {{$logs->links()}}
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- sellers product content area end -->
@endsection


@push('scripts')
  {{-- jquery datetimepicker JS --}}
  <script src="{{asset('assets/user/js/jquery.datetimepicker.full.min.js')}}"></script>
  <script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('#from_date').datetimepicker({
        format:"d-m-Y",
        timepicker:false
      });

      $('#to_date').datetimepicker({
        format:"d-m-Y",
        timepicker:false
      });
    });
    function delproduct(e, pid) {
      e.preventDefault();
      swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to get back this product!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            url: '{{route('vendor.product.delete')}}',
            type: 'POST',
            data: {
              id: pid
            },
            success: function(data) {
              console.log(data);
              if (data == "success") {
                  window.location = '{{url()->current()}}';
              }
            }
          });
        }
      });
    }
  </script>
@endpush
