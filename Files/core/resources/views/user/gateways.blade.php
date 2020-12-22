
@extends('layout.master')

@section('title', 'Payment Gateways')

@section('headertxt', 'Select Payment Gateway')

@push('styles')
<style media="screen">
  .gateways-container {
    padding: 50px 0px 30px 0px;
  }
  .list-group-item {
    font-size: 13px;
  }
</style>
@endpush

@section('content')
  <div class="gateways-container">
    <div class="container">
        @foreach ($gateways as $gateway)
          @if ($gateway->id < 900)
            @if ($loop->iteration % 4 == 1)
            <div class="row"> {{-- .row start --}}
            @endif
            <div class="col-md-3">
              <div class="card">
                <div class="" style="padding:5px;">
                  <img class="card-img-top" src="{{asset('assets/gateway/'.$gateway->id. '.jpg')}}" alt="Card image cap">
                </div>
                <div class="card-body text-center">
                  <h5 class="card-title">{{$gateway->name}}</h5>
                  <form method="post" action="{{route('user.paymentDataInsert')}}">
                    @csrf
                    @php
                      $userid = Auth::user()->id;
                    @endphp
                    <input type="hidden" name="amount" value="{{getTotal($userid)}}">
                    <input type="hidden" name="gateway" value="{{$gateway->id}}">
                    <input type="hidden" name="orderid" value="{{$orderid}}">
                    <button class="btn btn-block btn-primary" type="submit">Select</button>
                  </form>
                </div>
              </div>
            </div>

            @if ($loop->iteration % 4 == 0)
            </div> {{-- .row end --}}
            <br>
            @endif
          @endif
        @endforeach
    </div>
  </div>
  <br><br>
@endsection
