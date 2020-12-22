@extends('layout.master')

@push('styles')
<style media="screen">
  .withdraw-container {
    padding: 50px 0px 0px 0px;
  }
  .list-group-item {
    font-size: 13px;
  }
</style>
@endpush

@section('title', 'Withdraw Methods')

@section('headertxt', 'Withdraw Methods')

@section('content')
<div class="withdraw-container">
  <div class="container">
  @foreach ($wms as $wm)
    @if ($loop->iteration % 4 == 1)
    <div class="row"> {{-- .row start --}}
    @endif
    <div class="col-md-3">
      <div class="card">
        <div class="" style="padding:5px;">
          <img class="card-img-top" src="{{asset('assets/withdraw/'.$wm->logo)}}" alt="Card image cap">
        </div>
        <div class="card-body">
          <h5 class="card-title" id="method{{$wm->id}}">{{$wm->name}}</h5>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><strong>LIMIT </strong>({{$wm->min_limit}} - {{$wm->max_limit}}) {{$gs->base_curr_text}}</li>
          <li class="list-group-item"><strong>Charge</strong> {{$wm->fixed_charge}} {{$gs->base_curr_text}} + {{$wm->percentage_charge}}  %</li>
        </ul>
        <div class="card-body">
          <button onclick="showWithdrawMoneyModal({{$wm->id}}, document.getElementById('method{{$wm->id}}').innerHTML)" type="button" class="btn btn-block btn-primary">Withdraw Money</button>
        </div>
      </div>
    </div>
    @if ($loop->iteration % 4 == 0)
    </div> {{-- .row end --}}
    <br>
    @endif

  @endforeach
  </div>
</div>
<br><br>

  {{-- Request Withdraw Modal --}}
  @includeif('vendor.withdrawMoney.requestWithdraw')
@endsection
