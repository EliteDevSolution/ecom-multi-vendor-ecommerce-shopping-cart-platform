@extends('admin.layout.master')

@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h1>Category Management</h1>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
           <div class="tile">
              <h3 class="tile-title float-left">Categories List</h3>
              <div class="float-right icon-btn">
                <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank" class="btn btn-info">
                  <i class="fa fa-info"></i> Font Awesome Codes
                </a>
                 <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                   <i class="fa fa-plus"></i> Add Category
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
                @if (count($cats) == 0)
                  <h2 class="text-center">NO CATEGORY FOUND</h2>
                @else
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">SL</th>
                           <th scope="col">Name</th>
                           <th scope="col">Status</th>
                           <th>Subcategories</th>
                           <th scope="col">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                          @foreach ($cats as $key => $cat)
                            <tr>
                               <td>{{$key+1}}</td>
                               <td>{{$cat->name}}</td>
                               <td>
                                 @if ($cat->status == 1)
                                   <h4 style="display:inline-block;"><span class="badge badge-success">Active</span></h4>
                                 @elseif ($cat->status == 0)
                                   <h4 style="display:inline-block;"><span class="badge badge-danger">Deactive</span></h4>
                                 @endif
                               </td>
                               <td>
                                 <a href="{{route('admin.subcategory.index', $cat->id)}}" class="btn btn-info"><i class="fa fa-eye"></i> View</a>
                               </td>
                               <td>
                                 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal{{$cat->id}}">Edit</button>
                               </td>
                            </tr>
                            @includeif('admin.category.partials.edit')
                          @endforeach
                     </tbody>
                  </table>
                @endif
              </div>

              <div class="text-center">
                {{$cats->links()}}
              </div>
           </div>
        </div>
     </div>
  </main>

  {{-- Gateway Add Modal --}}
  @includeif('admin.category.partials.add')
@endsection
