@extends('admin.layout.master')


@section('content')
  <main class="app-content">
     <div class="app-title">
        <div>
           <h3 class="page-title uppercase bold">
             Comments
           </h3>
        </div>
        <ul class="app-breadcrumb breadcrumb">
           <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ul>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="tile">
              @if (count($ops) == 0)
                <h1 class="text-center"> NO DATA FOUND !</h1>
              @else
                <table class="table table-bordered" style="width:100%;">
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th>Shop Name</th>
                      <th>Product Title</th>
                      <th>Comment Type</th>
                      <th>Comment</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($ops as $key => $op)
                      <tr>
                          <td class="padding-top-40"><a target="_blank" href="">{{$op->user->username}}</a></td>
                          <td class="padding-top-40"><a target="_blank" href="">{{$op->vendor->shop_name}}</a></td>
                          <td class="padding-top-40"><a target="_blank" href="{{route('user.product.details', [$op->product->slug, $op->product->id])}}">{{strlen($op->product->title) > 30 ? substr($op->product->title, 0, 30) . '...' : $op->product->title}}</a></td>
                          <td>
                            @if ($op->comment_type == 'Complain')
                              <span class="badge badge-danger">Complain</span>
                            @elseif ($op->comment_type == 'Suggestion')
                              <span class="badge badge-success">Suggestion</span>
                            @endif
                          </td>
                          <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#commentModal{{$op->id}}">Comment</button>
                          </td>
                          <td>{{date('jS F, Y', strtotime($op->created_at))}}</td>
                      </tr>

                      <!-- Comment Modal -->
                      <div class="modal fade" id="commentModal{{$op->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">Comment</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <textarea class="form-control" name="name" rows="5" cols="80" readonly>{{$op->comment}}</textarea>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </tbody>
                </table>
              @endif

               <!-- print pagination -->
               <div class="row">
                 <div class="col-md-12">
                   <div class="text-center">
                      {{$ops->links()}}
                   </div>
                 </div>
               </div>
               <!-- row -->
        </div>
     </div>
   </div>
  </main>

@endsection
