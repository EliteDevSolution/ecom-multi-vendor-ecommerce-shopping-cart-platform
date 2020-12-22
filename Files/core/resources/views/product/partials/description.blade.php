<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header base-bg">
        <h4 class="title no-margin white-txt">Product Descriptions</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            {!!$product->description!!}
          </div>
        </div>

        <div class="card bg-light py-3 px-3">
           <div class="row">
             <div class="col-md-4 text-center">
                 <div class="card">
                     <div class="card-header base-bg">
                       <h4 class="text-white mb-0">
                         <i class="fa fa-map-marker"></i>
                         In {{$gs->main_city}} delivery charge
                       </h4> 
                     </div>
                     <div class="card-body text-left">
                       <ul>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> Cash on delivery charge - <strong>{{$gs->in_cash_on_delivery}} {{$gs->base_curr_text}}</strong>
                         </li>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> If you pay advance then delivery charge - <strong>{{$gs->in_advanced}} {{$gs->base_curr_text}}</strong>
                         </li>
                       </ul> 
                     </div>
                 </div>
             </div>
             <div class="col-md-4 text-center">
                 <div class="card">
                     <div class="card-header base-bg">
                       <h4 class="text-white mb-0">
                         <i class="fa fa-map-marker"></i>
                         Around {{$gs->main_city}} delivery charge
                       </h4> 
                     </div>
                     <div class="card-body text-left">
                       <ul>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> Cash on delivery charge - <strong>{{$gs->around_cash_on_delivery}} {{$gs->base_curr_text}}</strong>
                         </li>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> If you pay advance then delivery charge - <strong>{{$gs->around_advanced}} {{$gs->base_curr_text}}</strong>
                         </li>
                       </ul> 
                     </div>
                 </div>
             </div>
             <div class="col-md-4 text-center">
                 <div class="card">
                     <div class="card-header base-bg">
                       <h4 class="text-white mb-0">
                         <i class="fa fa-map-marker"></i>
                         Other Places
                       </h4> 
                     </div>
                     <div class="card-body text-left">
                       <ul>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> Cash on delivery charge - <strong>{{$gs->world_cash_on_delivery}} {{$gs->base_curr_text}}</strong>
                         </li>
                         <li>
                           <i class="fa fa-check-circle base-txt"></i> If you pay advance then delivery charge - <strong>{{$gs->world_advanced}} {{$gs->base_curr_text}}</strong>
                         </li>
                       </ul>

                     </div>
                 </div> 
                 
             </div>
           </div>
        </div>

        <div class="row refund_policy">
            <div class="col-md-12">
                <h3 class="base-txt"><i class="fa fa-check-circle"></i> Refund Policy</h3>
                <div class="">{!! $gs->refund_policy !!}</div>
            </div>
        </div>

        <div class="row replacement_policy">
            <div class="col-md-12">
                <h3 class="base-txt"><i class="fa fa-check-circle"></i> Replacement Policy</h3>
                <div class="">{!! $gs->replacement_policy !!}</div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
