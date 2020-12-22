@extends('layout.master')

@section('title', 'Privacy Policy')
@section('headertxt', 'Privacy Policy')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12 py-5">
        {!!$gs->privacy!!}
      </div>
    </div>
  </div>
@endsection
