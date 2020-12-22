<div class="row">
  <div class="col-sm-8 offset-md-2">
    <h2 class="base-txt my-2 text-center">Give a review</h2>
    <form id="reviewform" class="" onsubmit="reviewsubmit(event)">
      {{csrf_field()}}
      <input type="hidden" name="product_id" value="{{Request::route('id')}}">
      <input type="hidden" name="rating_helper" value="">
      <div class="form-group text-center">
        <label for="">Rating out of 5</label>
        <div id="writeRateYo" style="margin:0 auto;"></div>
        <div class="counter" style="font-weight: bold;color: black;"></div>
        <p id="errrating" class="text-danger"></p>
      </div>
      <div class="form-group">
        <textarea name="comment" class="form-control" rows="5" cols="120" placeholder="Comment (Optional)"></textarea>
      </div>
      <div class="form-group text-center">
        <button type="submit" class="btn base-bg white-txt">Submit</button>
      </div>
    </form>
  </div>
</div>
