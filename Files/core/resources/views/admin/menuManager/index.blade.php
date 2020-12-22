@extends('admin.layout.master')

@push('styles')
<style media="screen">
  h3, h4 {
    margin: 0px;
  }
</style>
@endpush

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div class="row" style="width:100%;">
          <div class="col-md-6">
            <h1 class="d-inline-block">Menu Management</h1>
          </div>
          <div class="col-md-6 text-right">
            <a href="{{route('admin.menuManager.add')}}" class="float-right btn btn-primary">Add Menu</a>
          </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">

          <div class="tile">

            <!---ROW-->
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-12">


                   <div class="card">
                     <div class="card-header bg-primary" style="color:white;">
                       <h4><i class="fa fa-list"></i> MENU LIST</h4>
                     </div>
                     <div class="card-body">
                       @if (count($menus) == 0)
                         <h1 class="text-center">NO DATA FOUND</h1>
                       @else
                         <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                               <thead>
                                  <tr>
                                     <th> # </th>
                                     <th> Menu Name </th>
                                     <th> Menu Title </th>
                                     <th> Action </th>
                                  </tr>
                               </thead>
                               <tbody>
                                 @foreach ($menus as $menu)
                                   <tr>
                                      <td>{{$loop->iteration}}</td>
                                      <td>{{$menu->name}}</td>
                                      <td>{{$menu->title}}</td>
                                      <td>
                                        <a class="btn btn-info btn-sm" href="{{route('admin.menuManager.edit', $menu->id)}}">
                                        <i class="fa fa-pencil"></i> Edit
                                        </a>
                                         <button type="button" class="btn btn-danger btn-sm delete_button" data-toggle="modal" data-target="#DelModal{{$menu->id}}" data-id="2">
                                         <i class="fa fa-times"></i> DELETE
                                         </button>
                                      </td>
                                   </tr>
                                   <!-- Modal for DELETE -->
                                   <!-- Modal -->
                                   <div class="modal fade" id="DelModal{{$menu->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                     <div class="modal-dialog modal-dialog-centered" role="document">
                                       <div class="modal-content">
                                         <div class="modal-header">
                                           <h5 class="modal-title text-center" id="exampleModalCenterTitle">
                                             Are you sure you want to delete this page?
                                           </h5>
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                           </button>
                                         </div>
                                         <div class="modal-body text-center">
                                           <form style="display:inline-block;" class="" action="{{route('admin.menuManager.delete', $menu->id)}}" method="post">
                                              {{csrf_field()}}
                                              <button type="submit" class="btn btn-success">Yes</button>
                                           </form>
                                           <button class="btn btn-danger" type="button" data-dismiss="modal">No</button>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                 @endforeach
                               </tbody>
                            </table>
                         </div>
                       @endif
                     </div>
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
