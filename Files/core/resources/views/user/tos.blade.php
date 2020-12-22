@extends('layout.master')

@section('title', 'Terms & Condtions')
@section('headertxt', 'Terms & Condtions')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12 py-5">
        {!!$gs->tos!!}
      </div>
    </div>
  </div>
@endsection
