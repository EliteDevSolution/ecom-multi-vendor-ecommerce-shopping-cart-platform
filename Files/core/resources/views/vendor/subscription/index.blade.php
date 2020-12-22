@extends('layout.master')

@push('styles')
  <style media="screen">
  .package-container {
    padding: 50px 0px;
  }
  .package-container h2 {
    margin-bottom: 20px;
    font-size: 40px;
  }
  .package-desc {
    min-height: 220px;
  }
  h5.card-title {
    margin: 0px;
    text-align: center;
  }
  </style>
@endpush

@section('title', 'Subscription Packages')

@section('headertxt', 'Subscription Packages')

@section('content')
  <div class="">
    <div class="container package-container">
      <div class="row">
        <div class="col-md-12">
          @php
            $vendor = Auth::guard('vendor')->user();
          @endphp

          <div id="successAlert" class="alert alert-success alert-dismissible fade show d-none">
            <p><strong class="text-success">Your package is valid till {{date('jS M, Y', strtotime($vendor->expired_date))}} and can upload {{$vendor->products}} products.</strong></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="dangerAlert" class="alert alert-danger alert-dismissible fade show d-none">
            <p><strong class="text-danger">You need to buy a package to upload products.</strong></p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          @foreach ($packages as $package)
            @if ($loop->iteration % 4 == 1)
            <div class="row"> {{-- .row start --}}
            @endif
            <div class="col-md-3">
              <div class="card" style="">
                <div class="card-header base-bg">
                  <h5 class="card-title text-white">{{$package->title}}</h5>
                </div>
                <div class="card-body package-desc">
                  <p class="card-text">
                    {{$package->s_desc}}
                  </p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong>Price: </strong>{{$package->price}} {{$gs->base_curr_text}}</li>
                  <li class="list-group-item"><strong>Products:</strong> {{$package->products}}</li>
                  <li class="list-group-item"><strong>Validaty:</strong> {{$package->validity}} days</li>
                </ul>
                <div class="card-body">
                  <div class="text-center">
                    <button type="button" class="btn btn-block base-bg white-txt" onclick="buy({{$package->id}});">Buy</button>
                  </div>
                </div>
              </div>
            </div>
            @if ($loop->iteration % 4 == 0)
            </div> {{-- .row end --}}
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>

  $(window).load(function(){
    $.get(
      '{{route('package.validitycheck')}}',
      function(data) {
        // console.log(data);

        if (data.products == 0) {
          $("#dangerAlert").addClass('d-block');
          $("#successAlert").addClass('d-none');
        } else if (data.products > 0) {
          $("#successAlert").addClass('d-block');
          $("#dangerAlert").addClass('d-none');
        }
      }
    );
  });

  function buy(id) {
    swal({
      title: "Confirmation",
      text: "Are you sure, you want to buy this package?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willBuy) => {
      if (willBuy) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var fd = new FormData();
        fd.append('packid', id);
        $.ajax({
          url: '{{route('package.buy')}}',
          type: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function(data) {
            console.log(data);
            if (data == "success") {
              window.location = '{{url()->current()}}';
            }
            if (data == "b_short") {
              swal("You dont have enough balance to buy this package!", {
                icon: "error",
              });
            }
          }
        })
      }
    });
  }
</script>
@endpush
