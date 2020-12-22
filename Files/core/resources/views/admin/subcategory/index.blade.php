@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Subcategory Under <strong>{{$category->name}}</strong> Category</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title pull-left">Subcategories List</h3>
              <div class="pull-right icon-btn">
                 <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                   <i class="fa fa-plus"></i> Add Subcategory
                 </button>
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
                @if (count($subcats) == 0)
                  <h2 class="text-center">NO SUBCATEGORY FOUND</h2>
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
                          @foreach ($subcats as $key => $subcat)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$subcat->name}}</td>
                               <td>
                                 @if ($subcat->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($subcat->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal{{$subcat->id}}"><i class="fa fa-pencil-square"></i> Edit</button>
                               </td>
                            </tr>
                            @includeif('admin.subcategory.partials.edit')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

           </div>
        </div>
     </div>
  </main>

  @includeif('admin.subcategory.partials.add')
@endsection
