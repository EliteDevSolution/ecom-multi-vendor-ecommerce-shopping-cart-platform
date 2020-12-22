<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="{{route('admin.subcategory.store')}}" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Subcategory</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              {{csrf_field()}}
              <input type="hidden" name="category_id" value="{{$category->id}}">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <strong>Subcategory Name</strong>
                      <input type="text" value="{{old('name')}}" class="form-control" id="name" name="name" placeholder="Enter Subcategory Name" >
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <strong>Attributes (Optional)</strong>
                      <div class="">
                        @foreach ($pas as $pa)
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" name="attributes[]" type="checkbox" id="pa{{$pa->id}}" value="{{$pa->id}}">
                            <label class="form-check-label" for="pa{{$pa->id}}">{{$pa->name}}</label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">ADD</button>
          </div>
      </form>
    </div>
  </div>
</div>
