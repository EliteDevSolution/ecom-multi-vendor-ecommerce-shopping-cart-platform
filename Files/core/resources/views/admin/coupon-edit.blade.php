@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div class="row" style="width:100%">
          <div class="col-md-6">
            <h1 class="float-left">Edit Coupon</h1>
          </div>
          <div class="col-md-6">
            <a href="{{route('admin.coupon.index')}}" class="btn btn-primary float-right">Coupon Lists</a>
          </div>
        </div>
     </div>

     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <div class="tile-body">
                 <form role="form" method="POST" action="{{route('admin.coupon.update')}}" enctype="multipart/form-data">
                    <div class="form-body">
                       {{csrf_field()}}
                       <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
                       <div class="form-group">
                          <label><strong>Coupon Code</strong></label>
                          <input type="text" name="coupon_code" class="form-control input-lg" value="{{$coupon->coupon_code}}">
                          @if ($errors->has('coupon_code'))
                            <span style="color:red;">{{$errors->first('coupon_code')}}</span>
                          @endif
                       </div>
                       <div class="form-group">
                          <label><strong>Type</strong></label><br>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coupon_type" id="inlineRadio1" value="fixed" {{$coupon->coupon_type=='fixed'?'checked':''}} onchange="changePlaceholder(this.value)">
                            <label class="form-check-label" for="inlineRadio1">Fixed</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coupon_type" id="inlineRadio2" value="percentage" {{$coupon->coupon_type=='percentage'?'checked':''}} onchange="changePlaceholder(this.value)">
                            <label class="form-check-label" for="inlineRadio2">Percentage</label>
                          </div>
                          @if ($errors->has('type_helper'))
                            <p><span style="color:red;">{{$errors->first('type_helper')}}</span></p>
                          @endif
                          <input type="hidden" name="type_helper" value="">
                       </div>
                       <div class="form-group">
                          <label><strong>Amount</strong></label>
                          <input type="text" name="coupon_amount" class="form-control input-lg" value="{{$coupon->coupon_amount}}" {{empty($coupon->coupon_amount)?'disabled':''}}>
                          @if ($errors->has('coupon_amount'))
                            <span style="color:red;">{{$errors->first('coupon_amount')}}</span>
                          @endif
                       </div>
                       <div class="form-group" id="minamount">
                          <label><strong>Minimum Amount</strong></label>
                          <input type="text" name="minimum_amount" class="form-control input-lg" value="{{$coupon->coupon_min_amount}}" placeholder="Minimum Amount">
                          @if ($errors->has('minimum_amount'))
                            <span style="color:red;">{{$errors->first('minimum_amount')}}</span>
                          @endif
                       </div>
                       <div class="form-group">
                          <label><strong>Valid Till</strong></label>
                          <input id="validtill" type="text" name="valid_till" class="form-control input-lg" value="{{$coupon->valid_till}}" readonly>
                          @if ($errors->has('valid_till'))
                            <span style="color:red;">{{$errors->first('valid_till')}}</span>
                          @endif
                       </div>
                    </div>
                    <div class="form-actions">
                       <button type="submit" class="btn btn-primary btn-block btn-lg">Update</button>
                    </div>
                 </form>
              </div>
           </div>
        </div>
     </div>
  </main>
@endsection

@push('scripts')
  <script>
    var currtxt = '{{$gs->base_curr_text}}';
    $( function() {
      $( "#validtill" ).datepicker();
    } );
    $(document).ready(function() {
      var type = $("input[name='coupon_type']:checked").val();
      console.log(type);
      if (type == 'fixed') {
        $("#minamount").addClass("d-block");
      } else {
        $("#minamount").addClass("d-none");
      }
    });

    function changePlaceholder(val) {
      document.getElementsByName('coupon_amount')[0].disabled = false;
      document.getElementsByName('coupon_amount')[0].value = '';
      if (val == 'fixed') {
        document.getElementsByName('coupon_amount')[0].placeholder = 'Enter discount in ' + currtxt;
        $("#minamount").addClass("d-block");
        $("#minamount").removeClass("d-none");
      } else if (val == 'percentage') {
        document.getElementsByName('coupon_amount')[0].placeholder = 'Enter discount in Percentage';
        $("#minamount").addClass("d-none");
        $("#minamount").removeClass("d-block");
      }
    }
  </script>
@endpush
