
<!-- Modal -->
<div class="modal fade" id="withdrawMoneyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Money Withdraw Request using <strong id="methodNameInModal"></strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="withdrawRequestForm" class="" onsubmit="return false;">
          {{csrf_field()}}
          <input type="hidden" id="wmHiddenID" name="wmID" value="">
          <div class="form-group">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">{{$gs->base_curr_text}}</span>
              </div>
              <input type="text" name="amount" class="form-control" placeholder="Enter amount you want to withdraw" aria-label="Amount" aria-describedby="basic-addon1">
            </div>
            <p class="error-message-withdraw-request text-danger"></p>
          </div>
          <div class="form-group">
            <textarea id="withdrawRequestTextarea" class="form-control" name="details" rows="3" cols="80" placeholder="Enter account details..."></textarea>
            <p class="error-message-withdraw-request text-danger"></p>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button onclick="submitWithdrawRequest()" type="button" class="btn btn-primary">Send withdraw request</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



{{-- show withdraw Request modal --}}
@push('scripts')
  <script>
    function showWithdrawMoneyModal(wmID, methodName) {
      console.log(wmID);
      $("#withdrawMoneyModal").modal('show');
      document.getElementById('wmHiddenID').value = wmID;
      // adding method name dynamically to the modal header...
      document.getElementById("methodNameInModal").innerHTML = methodName;
      // creating placeholder dynamically with method name for textarea...
      document.getElementById('withdrawRequestTextarea').setAttribute('placeholder', 'Enter your ' + methodName + ' details');
    }
  </script>
@endpush

@push('scripts')
  <script>
    function submitWithdrawRequest() {
      var form = document.getElementById('withdrawRequestForm');
      var fd = new FormData(form);

      $.ajax({
        url: '{{route('vendor.withdrawRequest.store')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          console.log(data);

          var emwr = document.getElementsByClassName("error-message-withdraw-request");
          // after returning from the controller we are clearing the
          // previous error messages...
          for(i=0; i<emwr.length; i++) {
            emwr[i].innerHTML = '';
          }

          if(data == "success") {
            $("#withdrawMoneyModal").modal('hide');
            document.getElementById('withdrawRequestForm').reset();
            swal("Success", "Withdraw request sent successfully", "success");
          }
          if(typeof data.error != 'undefined') {
            if (typeof data.amount != 'undefined') {
              emwr[0].innerHTML = data.amount[0];
            }
            if (typeof data.details != 'undefined') {
              emwr[1].innerHTML = data.details[0];
            }
          }
        }
      });
    }
  </script>
@endpush
