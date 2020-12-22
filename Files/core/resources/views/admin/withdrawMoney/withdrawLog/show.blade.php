@extends('admin.layout.master')

{{-- All necessary JS for NIC Editor --}}
@push('nicedit-scripts')
  <script src="{{asset('assets/nic-edit/nicEdit.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    bkLib.onDomLoaded(function() {
      new nicEditor({iconsPath : '{{asset('assets/nic-edit/nicEditorIcons.gif')}}', fullPanel : true}).panelInstance('actionTextarea');
    });
  </script>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold"> Withdraw Details</h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
              <div class="row">

                <div class="col-md-6">
                  <div id="withdrawInfo">
                     <div class="card">
                        <div class="card-header bg-primary">
                           <div style="color:white">
                              <h4><i class="fa fa-upload"></i> Withdraw Request</h4>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="table-scrollable">
                              <table class="table table-bordered table-hover">
                                 <tbody>
                                    <tr class="bold">
                                       <td> Requested By </td>
                                       <td>
                                          {{$withdraw->vendor->shop_name}}
                                          ( <i>{{$withdraw->vendor->email}}</i> )
                                       </td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Requested On </td>
                                       <td>{{$withdraw->created_at->format('l jS \\of F Y h:i:s A')}} </td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Transaction # </td>
                                       <td>{{$withdraw->trx}}</td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Method </td>
                                       <td>{{$withdraw->withdrawMethod->name}}</td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Amount </td>
                                       <td>{{$withdraw->amount}} {{$gs->base_curr_text}}</td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Charge </td>
                                       <td>{{$withdraw->charge}} {{$gs->base_curr_text}}</td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Status </td>
                                       <td><button class="btn btn-info"> {{$withdraw->status}}</button></td>
                                    </tr>
                                    <tr class="bold">
                                       <td> Details </td>
                                       <td>{!!nl2br($withdraw->details)!!}</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <i style="color: red;"> *** Charge Already taken. Send <strong>{{$withdraw->amount}}  {{$gs->base_curr_text}}</strong> To The User</i>
                        </div>
                     </div>

                   </div>
                </div>
                 <div class="col-md-6">
                    <div class="card">
                       <div class="card-header bg-primary">
                          <div style="color:white;">
                             <h4><i class="fa fa-cogs"></i> Take Action</h4>
                          </div>
                       </div>
                       <div class="card-body">
                          <form id="messageFormID" method="post">
                             <strong style="text-transform: uppercase;">Message or Reason</strong><br><br>
                             {{csrf_field()}}
                             <textarea id="actionTextarea" name="message" rows="8" style="width:100%;"></textarea>
                             <p id="withdrawLogMessageError" class="text-danger"></p>

                             <div class="row">
                                <div class="col-md-6">
                                   <button onclick="storeMessage('processed', {{$withdraw->id}})" type="button" name="val" value="1" class="btn btn-primary btn-block btn-lg">PROCESSED</button>
                                </div>
                                <div class="col-md-6">
                                   <button onclick="storeMessage('refunded', {{$withdraw->id}})" type="button" name="val" value="2" class="btn btn-danger btn-block btn-lg">REFUND</button>
                                </div>
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
              </div>
              <!-- row -->
        </div>
     </div>
   </div>
  </main>

@endsection


@push('scripts')
  <script>
    function storeMessage(status, wID) {
      var form = document.getElementById('messageFormID');
      var fd = new FormData(form);
      fd.append('wID', wID);
      fd.append('status', status);
      var nicE = new nicEditors.findEditor('actionTextarea');
      message = nicE.getContent();
      fd.append('message', message);
      $.ajax({
        url: '{{route('withdrawLog.message.store')}}',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          console.log(data);
          document.getElementById('withdrawLogMessageError').innerHTML = '';
          if(data == "success") {
            nicE.setContent('');
            document.getElementById('messageFormID').reset();
            $("#withdrawInfo").load(location.href + " #withdrawInfo");
            swal('Success', '', 'success');

          }
          if(typeof data.error != 'undefined') {
            document.getElementById('withdrawLogMessageError').innerHTML = data.message[0];
          }
          if (data != "success" && typeof data.error == 'undefined') {
            swal('Sorry', 'This is Demo version. You can not change anything.', 'error');
          }
        }
      });
    }
  </script>
@endpush
