@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Option Management</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title pull-left">All Options of <strong>{{$pa->name}}</strong></h3>
              <div class="pull-right icon-btn">
                <a class="btn btn-warning" href="{{route('admin.productattr.index')}}">
                  <i class="fa fa-list"></i> All Product Attributes
                </a>
                <a class="btn btn-info" data-toggle="modal" data-target="#addSub{{$pa->id}}">
                  <i class="fa fa-plus"></i> Add Option
                </a>
              </div>
              <p style="clear:both;margin:0px;"></p>
              <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
              </div>
              <div class="table-responsive">
                @if (count($options) == 0)
                  <h2 class="text-center">NO DATA FOUND</h2>
                @else
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Status</th>
                           <th scope="col">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($options as $key => $option)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$option->name}}</td>
                               <td>
                                 @if ($option->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($option->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal{{$option->id}}">Edit</button>
                               </td>
                            </tr>
                            @includeif('admin.options.partials.edit')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$options->links()}}
              </div>
           </div>
        </div>
     </div>
  </main>

  @includeif('admin.options.partials.add')
@endsection
