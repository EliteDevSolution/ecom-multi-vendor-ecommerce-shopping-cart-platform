<!-- header area start -->
<div class="hero-carousel owl-carousel owl-theme">
  @foreach ($sliders as $key => $slider)
    <div class="header-area-three header-bg-four home-six" style="background-image: url('{{asset('assets/user/interfaceControl/sliders/'.$slider->image)}}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="header-inner"><!-- header inner -->
                        <span class="subtitle">{{$slider->title}}</span>
                        <h1 class="title">{{$slider->bold_text}}</h1>
                        <p>{{$slider->small_text}}</p>
                            <div class="btn-wrapper">
                                <a href="{{$slider->url}}" class="boxed-btn">View Collections</a>
                            </div>
                    </div><!-- //. header inner -->
                </div>
            </div>
        </div>
    </div>
  @endforeach
</div>
<!-- header area end -->
