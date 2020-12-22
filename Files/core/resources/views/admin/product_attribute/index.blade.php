@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Product Attribute Management</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title float-left">All Product Attributes</h3>
              <div class="float-right icon-btn">
                <a class="btn btn-info" data-toggle="modal" data-target="#addModal">
                  <i class="fa fa-plus"></i> Add Product Attribute
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
                @if (count($pas) == 0)
                  <h2 class="text-center">NO DATA FOUND</h2>
                @else
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Status</th>
                           <th>All Options</th>
                           <th scope="col">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($pas as $key => $pa)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$pa->name}}</td>
                               <td>
                                 @if ($pa->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($pa->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <a class="btn btn-primary" href="{{route('admin.options.index', $pa->id)}}"><i class="fa fa-eye"></i> View</a>
                               </td>
                               <td>
                                 <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addSub{{$pa->id}}">Add Option</button>
                                 <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal{{$pa->id}}">Edit</button>
                               </td>
                            </tr>
                            @includeif('admin.product_attribute.partials.edit')
                            @includeif('admin.options.partials.add')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$pas->links()}}
              </div>
           </div>
        </div>
     </div>
  </main>

  @includeif('admin.product_attribute.partials.add')
@endsection
