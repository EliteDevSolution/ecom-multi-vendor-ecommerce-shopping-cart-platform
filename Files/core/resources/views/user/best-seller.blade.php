@extends('layout.master')

@section('title', 'Best Sellers')

@section('headertxt', 'Best Sellers')

@section('content')

    <div class="container">
      <div class="row">
    
            @foreach ($products as $product)
    
              <div class="col-lg-3 col-md-6">
                  <div class="single-new-collection-item "><!-- single new collections -->
                      <div class="thumb">
                          <img src="{{asset('assets/user/img/products/'.$product->previewimages()->first()->image)}}" alt="new collcetion image">
                          <div class="hover">
                              <a href="{{route('user.product.details', [$product->slug, $product->id])}}" class="view-btn"><i class="fa fa-eye"></i></a>
                          </div>
                      </div>
                      <div class="content">
                          <span class="category">{{$product->category->name}}</span>
                          <a href="{{route('user.product.details', [$product->slug, $product->id])}}"><h4 class="title">{{strlen($product->title) > 25 ? substr($product->title, 0, 25) . '...' : $product->title}}</h4></a>
                          @if (empty($product->current_price))
                            <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->price}}</span></div>
                          @else
                            <div class="price"><span class="sprice">{{$gs->base_curr_symbol}} {{$product->current_price}}</span> <del class="dprice">{{$gs->base_curr_symbol}} {{$product->price}}</del></div>
                          @endif
                      </div>
                  </div><!-- //. single new collections  -->
              </div>
            @endforeach
    
      </div>        
    </div>

@endsection
