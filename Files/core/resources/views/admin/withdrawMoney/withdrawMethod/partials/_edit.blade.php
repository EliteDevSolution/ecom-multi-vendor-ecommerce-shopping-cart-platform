{{-- Edit Modal --}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none; margin-top: 0px;">
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
        <div class="modal-header">
           <h4 class="modal-title" id="myModalLabel"> <b class="abir_act">Edit</b> Withdraw Method</h4>
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <form id="editWithdrawMethodForm" method="post" onsubmit="update(event)" enctype="multipart/form-data">
           {{csrf_field()}}
           <input type="hidden" id="wmID" name="wmID" value="">
           <div class="modal-body">
              <input class="form-control abir_id" type="hidden" name="id" value="1">
              <div class="row">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                       <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                          <img id="wimg" src="" alt="*" />
                       </div>
                       <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"> </div>
                       <div>
                          <span class="btn btn-success btn-file">
                          <span class="fileinput-new"> Change Logo </span>
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
                        <input id="methodName" class="form-control input-lg" name="name" placeholder="" type="text">
                        <p class="error-message-edit text-danger"></p>
                     </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                     <label><strong style="text-transform: uppercase;">Process Time</strong></label>
                     <div>
                        <div class="input-group mb-3">
                           <input id="processTime" class="form-control input-lg" name="processtm" value="" type="text">
                           <div class="input-group-append">
                             <span class="input-group-text" id="basic-addon2">DAYS</span>
                           </div>
                        </div>
                        <p class="error-message-edit text-danger"></p>
                     </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-primary">
                      <h4 style="color:white;">Limit Per Transaction</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                             <label><strong style="text-transform: uppercase;">MINIMUM</strong></label>
                             <div>
                                <div class="input-group mb-3">
                                   <input id="minimum" class="form-control input-lg" name="min" value="" type="text">
                                   <div class="input-group-append">
                                     <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                                   </div>
                                </div>
                             </div>
                             <p class="error-message-edit text-danger"></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                             <label><strong style="text-transform: uppercase;">MAXIMUM</strong></label>
                             <div>
                                <div class="input-group mb-3">
                                   <input id="maximum" class="form-control input-lg" name="max" value="" type="text">
                                   <div class="input-group-append">
                                     <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                                   </div>
                                </div>
                             </div>
                             <p class="error-message-edit text-danger"></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-primary">
                      <h4 style="color:white;">Charge Per Transaction</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                             <div>
                                <div class="input-group mb-3">
                                   <input id="charged" class="form-control input-lg" name="charged" value="" type="text">
                                   <div class="input-group-append">
                                     <span class="input-group-text" id="basic-addon2">{{$gs->base_curr_text}}</span>
                                   </div>
                                </div>
                             </div>
                             <p class="error-message-edit text-danger"></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                             <div>
                                <div class="input-group mb-3">
                                   <input id="chargep" class="form-control input-lg" name="chargep" value="" type="text">
                                   <div class="input-group-append">
                                     <span class="input-group-text" id="basic-addon2">%</span>
                                   </div>
                                </div>
                             </div>
                             <p class="error-message-edit text-danger"></p>
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
              <button type="submit" class="btn btn-primary">Save changes</button>
           </div>
        </form>
     </div>
   </div>
</div>

{{-- Showing withdraw method information of the method that is to be edited... --}}
@push('scripts')
   <script>
      function showEditModal(wmID) {
         console.log(wmID);
         var fd = new FormData();
         fd.append('wmID', wmID);
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
         $.get(
            '{{route('withdrawMethod.edit')}}',
            {
               wmID: wmID
            },
            function(data) {
               console.log(data);
               $("#wmID").val(data.id);
               $("#methodName").val(data.name);
               document.getElementById('wimg').src = '{{asset('assets/withdraw')}}' + '/' + data.logo;
               $("#minimum").val(data.min_limit);
               $("#maximum").val(data.max_limit);
               $("#charged").val(data.fixed_charge);
               $("#chargep").val(data.percentage_charge);
               $("#processTime").val(data.process_time);
            }
         );
      }
   </script>
@endpush

{{-- Update Withdraw method... --}}
@push('scripts')
   <script>
      function update(e) {
         e.preventDefault();
         var form = document.getElementById('editWithdrawMethodForm');
         var fd = new FormData(form);

         $.ajax({
            url: '{{route('withdrawMethod.update')}}',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
               console.log(data);
               var em = document.getElementsByClassName("error-message-edit");
               // after returning from the controller we are clearing the
               // previous error messages...
               for(i=0; i<em.length; i++) {
                  em[i].innerHTML = '';
               }

               // if data is saved in database successfully...
               if (data == "success") {
                  $("#myModal").modal('hide');
                  document.getElementById('editWithdrawMethodForm').reset();
                  $("#wmsContainerID").load(location.href + " #wmsContainerID");
               }

               // Showing error message in the HTML...
               if(typeof data.error != 'undefined') {
                  if(typeof data.name != 'undefined') {
                     em[0].innerHTML = data.name[0];
                  }
                  if(typeof data.processtm != 'undefined') {
                     em[1].innerHTML = data.processtm[0];
                  }
                  if(typeof data.min != 'undefined') {
                     em[2].innerHTML = data.min[0];
                  }
                  if(typeof data.max != 'undefined') {
                     em[3].innerHTML = data.max[0];
                  }
                  if(typeof data.charged != 'undefined') {
                     em[4].innerHTML = data.charged[0];
                  }
                  if(typeof data.chargep != 'undefined') {
                     em[5].innerHTML = data.chargep[0];
                  }
               }
               if(data != "success" && typeof data.error == 'undefined') {
                 $("#myModal").modal('hide');
                 swal('Sorry!', 'This is Demo version. You can not change anything.', 'error');
               }
            }
         });
      }
   </script>
@endpush
