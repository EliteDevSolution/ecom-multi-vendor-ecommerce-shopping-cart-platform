@extends('layout.master')

@section('title', 'Manage Products')

@section('headertxt', 'Manage Products')

@push('styles')
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
                          <div class="card-header clearfix">
                                  <h4 style="padding-top: 15px;" class="d-inline-block text-white">YOUR PRODUCTS</h4>
                                  <a href="{{route('vendor.product.create')}}" class="boxed-btn float-right">Add New Product</a>
                          </div>
                          <div class="sellers-product-inner">
                              <div class="bottom-content">
                                  <table class="table table-default" id="datatableOne">
                                      <thead>
                                          <tr>
                                              <th>Product</th>
                                              <th>Price</th>
                                              <th>quantity left</th>
                                              <th>Total Earnings</th>
                                              <th>Sales</th>
                                              <th>&nbsp;</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @foreach ($products as $product)
                                          @php
                                            $totalearning = \App\Orderedproduct::where('shipping_status', 2)
                                                                  ->where('refunded', '<>', 1)
                                                                  ->where('product_id', $product->id)->sum('product_total');
                                          @endphp
                                          <tr>
                                              <td>
                                                  <div class="single-product-item"><!-- single product item -->
                                                      <div class="thumb">
                                                        <a href="#">
                                                          <img style="width:60px;" src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="seller product image">
                                                        </a>
                                                      </div>
                                                      <div class="content">
                                                          <h4 class="title"><a target="_blank" href="{{route('user.product.details', [$product->slug,$product->id])}}">{{strlen($product->title) > 28 ? substr($product->title, 0, 28) . '...' : $product->title}}</a></h4>
                                                      </div>
                                                  </div><!-- //.single product item -->
                                              </td>
                                              <td class="padding-top-40">
                                                @if (!empty($product->current_price))
                                                  <del>{{$gs->base_curr_symbol}} {{$product->price}}</del> <span class="text-secondary">{{$gs->base_curr_symbol}} {{$product->current_price}}</span>
                                                @else
                                                  <span>{{$gs->base_curr_symbol}} {{$product->price}}</span>
                                                @endif
                                              </td>
                                              <td class="padding-top-40">
                                                @if ($product->quantity==0)
                                                  <span class="badge badge-danger">Out of stock</span>
                                                @else
                                                  {{$product->quantity}}
                                                @endif
                                              </td>
                                              <td class="padding-top-40">{{$gs->base_curr_symbol}} {{$totalearning}}</td>
                                              <td class="padding-top-40">{{$product->sales}}</td>
                                              <td class="padding-top-40">
                                                  <ul class="action">
                                                      <li><a target="_blank" href="{{route('user.product.details', [$product->slug,$product->id])}}"><i class="far fa-eye"></i></a></li>
                                                      <li><a target="_blank" href="{{route('vendor.product.edit', $product->id)}}"><i class="fas fa-pencil-alt"></i></a></li>
                                                      <li class="sp-close-btn"><a href="#" onclick="delproduct(event, {{$product->id}})"><i class="fas fa-times"></i></a></li>
                                                  </ul>
                                              </td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                  </table>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="text-center">
                                     {{$products->links()}}
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
  <script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
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
