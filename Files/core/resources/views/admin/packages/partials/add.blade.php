<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="{{route('admin.package.store')}}" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Package</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              {{csrf_field()}}
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                     <strong>Package Title</strong>
                     <input type="text" value="{{old('title')}}" class="form-control" id="name" name="title" placeholder="Enter package title" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                     <strong>Short Description</strong>
                     <textarea class="form-control" name="s_desc" rows="3" cols="80" placeholder="Enter short description">{{ old('s_desc') }}</textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                     <strong>Price</strong>
                     <input type="text" value="{{old('price')}}" class="form-control" id="name" name="price" placeholder="Enter price" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                     <strong>Products</strong>
                     <input type="number" value="{{old('products')}}" class="form-control" id="name" name="products" placeholder="Enter number of products" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                     <strong>Validity (in Days)</strong>
                     <input type="number" value="{{old('validity')}}" class="form-control" id="name" name="validity" placeholder="Enter number of valid days" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                 <strong>Status</strong>
                 <select class="form-control" name="status">
                 <option value="1">Active</option>
                 <option value="0">Deactive</option>
                 </select>
              </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">ADD</button>
          </div>
      </form>
    </div>
  </div>
</div>
