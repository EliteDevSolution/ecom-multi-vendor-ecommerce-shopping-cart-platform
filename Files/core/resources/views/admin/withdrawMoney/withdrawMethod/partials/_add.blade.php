{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none; margin-top: 0px;">
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
        <div class="modal-header">
           <h4 class="modal-title" id="myModalLabel"> <b class="abir_act">Add</b> Withdraw Method</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
        </div>
        <form id="addMethodForm" method="POST" action="{{route('withdrawMethod.store')}}" enctype="multipart/form-data">
           <div class="modal-body">
              {{csrf_field()}}
              <input class="form-control" type="hidden" name="id" value="1">
              <div class="row">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                       <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                          <img src="http://via.placeholder.com/800X800" alt="*" />
                       </div>
                       <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"> </div>
                       <div>
                          <span class="btn btn-success btn-file">
                          <span class="fileinput-new"> Choose Logo </span>
                          <span class="fileinput-exists"> Change </span>
                          <input type="file" name="wimg"> </span>
                          <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                       </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                     <label><strong style="text-transform: uppercase;">Method Name</strong></label>
                     <div>
                        <input class="form-control input-lg" name="methodName" placeholder="" type="text">
                        <p class="error-message text-danger"></p>
                     </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label><strong style="text-transform: uppercase;">Process Time</strong></label>
                    <div class="input-group mb-3">
                      <input name="processTime" value="" type="text" class="form-control input-lg" aria-label="Username" aria-describedby="basic-addon1">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">DAYS</span>
                      </div>
                    </div>
                    <p class="error-message text-danger"></p>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="card ">
                    <div class="card-header bg-primary">
                      <h4 style="color:white;">LIMIT PER TRANSACTION</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label><strong style="text-transform: uppercase;">MINIMUM</strong></label>
                            <div class="input-group mb-3">
                              <input type="text" class="form-control input-lg" name="minimum" value="" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label><strong style="text-transform: uppercase;">MAXIMUM</strong></label>
                            <div class="input-group mb-3">
                              <input type="text" class="form-control input-lg" name="maximum" value="" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-primary">
                      <h4 style="color:white;">CHARGE PER TRANSACTION</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group mb-3">
                              <input type="text" class="form-control input-lg" name="charged" value="" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group mb-3">
                              <input type="text" class="form-control input-lg" name="chargep" value="" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">%</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

           </div>
           <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add Method</button>
           </div>
        </form>
     </div>
   </div>
</div>
