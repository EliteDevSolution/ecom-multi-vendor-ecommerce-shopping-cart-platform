@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> Withdraw Method Management</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
               <div>
                  <h3 class="page-title  uppercase bold">
                     <button type="button" class="btn btn-primary  btn-md pull-right edit_button" data-toggle="modal" data-target="#addModal" data-act="Add New" data-name="" data-id="0">
                     <i class="fa fa-plus"></i>  ADD NEW
                     </button>
                     <p style="margin:0px;clear:both;"></p>
                  </h3>
                  <hr>
                  <div id="wmsContainerID">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif

                     <div class="row">
                        <div class="col-md-12">
                           <!-- BEGIN EXAMPLE TABLE PORTLET-->
                           <div>
                              <div>
                                @if (count($wms) == 0)
                                <h3 class="text-center">NO WITHDRAW METHOD FOUND</h3>
                                @else
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th>Name</th>
                                      <th>Limit/Trx</th>
                                      <th>Charge/Trx</th>
                                      <th>Process Time</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($wms as $wm)
                                    <tr>
                                       <td>{{$wm->name}}</td>
                                       <td><b>{{$wm->min_limit}} </b> TO <b>{{$wm->max_limit}} {{$gs->base_curr_text}}</b></td>
                                       <td><b>{{$wm->fixed_charge}} </b> + <b>{{$wm->percentage_charge}} %</b></td>
                                       <td><b>{{$wm->process_time}}</b></td>
                                       <td>
                                          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" data-act="Edit" data-ptm="2-4" data-cp="2" data-cd="1" data-max="10000" data-min="100" data-name="bKash" data-id="1" onclick="showEditModal({{$wm->id}})">
                                          <i class="fa fa-edit"></i> EDIT
                                          </button>
                                          <button id="enableDisableBtnID{{$wm->id}}" type="button" class="btn {{($wm->deleted==1) ? 'btn-success' : 'btn-danger'}} btn-sm delete_button" data-toggle="modal" data-target="#DelModal" data-id="1" onclick="enableDisableWM({{$wm->id}})">{{($wm->deleted==1)?'Enable':'Disable'}}</button>
                                       </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                                @endif
                              </div>
                           </div>
                           <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                     </div>
                     <!-- ROW-->
                  </div>

               </div>
               <!-- END CONTENT BODY -->
            </div>


          {{-- Add Modal --}}
          @includeIf('admin.withdrawMoney.withdrawMethod.partials._add')

          {{-- Delete Modal --}}
          @includeIf('admin.withdrawMoney.withdrawMethod.partials._delete')

          {{-- Edit Modal --}}
          @includeIf('admin.withdrawMoney.withdrawMethod.partials._edit')
        </div>
     </div>
  </main>

@endsection
