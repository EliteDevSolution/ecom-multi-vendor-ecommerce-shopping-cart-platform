@extends('layout.master')

@section('title', "$menu->title")

@section('headertxt', "$menu->title")

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12 py-5">
        {!!$menu->body!!}
      </div>
    </div>
  </div>
@endsection
