<div class="comments">
  @if ($product->productreviews()->count() == 0)
    <div class="card">
      <div class="card-body">
        <h3 class="text-center base-txt">No Review Given Yet</h3>
      </div>
    </div>

  @else
    <div class="row">
      <div class="col-md-12 text-center">
        <h2 class="mt-2" style="color:#f0932b">{{round(\App\ProductReview::where('product_id', $product->id)->avg('rating'), 2)}}/5.0</h2>
        Based on {{\App\ProductReview::where('product_id', $product->id)->count()}} reviews
      </div>
    </div>

    <div id="comments">
      @foreach ($product->productreviews()->orderBy('id', 'DESC')->get() as $productreview)
        <div class="comment-wrap">
           <div class="comment-block">
              <h4>{{$productreview->user->username}}</h4>
              <p class="comment-text">
                {{$productreview->comment}}
              </p>
              <div class="bottom-comment">
                 <div class="comment-date">{{date('M d, Y @ g:i A', strtotime($productreview->created_at))}}</div>
                 <ul class="comment-actions">
                    <div id="rateYo{{$productreview->id}}"></div>
                 </ul>
              </div>
           </div>
        </div>

      @endforeach

    </div>


  @endif

</div>

@push('scripts')
<script>
  $(document).ready(function() {
    $.get("{{route('user.productratings', $product->id)}}", function(data){
      console.log(data);
      for (var i = 0; i < data.length; i++) {
        $("#rateYo"+data[i].id).rateYo({
          rating: data[i].rating,
          readOnly: true,
          starWidth: "16px"
        });
      }

    });
  });
</script>
@endpush
