<!-- Modal -->
<div class="modal fade" id="editModal{{$subcat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="{{route('admin.subcategory.update')}}" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Edit Subcategory
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              {{csrf_field()}}
              <input type="hidden" name="statusId" value="{{$subcat->id}}">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12 mb-10">
                     <strong>Subcategory Name</strong>
                     <input type="text" value="{{$subcat->name}}" class="form-control" id="name" name="name" placeholder="Enter subcategory name" >
                  </div>
                  <div class="col-md-12 mb-10">
                    <strong>Status</strong>
                    <select class="form-control" name="status">
                      <option value="1" {{($subcat->status==1) ? 'selected' : ''}}>Active</option>
                      <option value="0" {{($subcat->status==0) ? 'selected' : ''}}>Deactive</option>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <strong>Attributes (Optional)</strong>
                      <div class="">
                        @php
                          $attrarr = json_decode($subcat->attributes, true);
                          if (empty($attrarr)) {
                            $attrarr['attributes'] = [];
                          }
                        @endphp
                        @foreach ($pas as $pa)
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" name="attributes[]" type="checkbox" id="pa{{$pa->id}}" value="{{$pa->id}}" {{in_array($pa->id, $attrarr['attributes'])? 'checked' : ''}}>
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
            <button type="submit" class="btn btn-primary">UPDATE</button>
          </div>
      </form>
    </div>
  </div>
</div>
