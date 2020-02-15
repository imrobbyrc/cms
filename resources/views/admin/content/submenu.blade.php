@extends('admin.main')
@section('title')
    <title>Sub Menu</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Sub Menu</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Sub Menu</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Sub Menu List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="submenu">
          <thead>
            <tr>
              <th>#</th>
              <th>Menu</th>
              <th>Sub Menu</th>
              <th>Title</th>
              <th>Description</th>
              <th>Link</th>
              <th>Image</th>
              <th>Status</th>
              <th>Layout</th>
              <th>Priority</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div> 

<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
          @endif
          <div class="card-body">
            <div class="form-group">
              <label>Menu</label>
              <select class="form-control" required="" name="menuId" onchange="cekMenu()">
                <option value="">Pilih Menu</option>
                <option value="1">Brand</option>
                <option value="2">Solution</option>
              </select>
              <div class="invalid-feedback">
                Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Sub Menu</label>
              <input type="text" class="form-control" required="" name="submenus" value="{{ old('submenus') }}">
              <div class="invalid-feedback">
                Sub Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="{{ old('title') }}">
              <div class="invalid-feedback">
                Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
                <textarea id="dec" class="summernote-simple" required="" name="description" value="{{ old('description') }}"></textarea>
              <div class="invalid-feedback">
               Description Required
              </div>
            </div>
            <div class="form-group">
              <label>Link</label>
              <input type="text" class="form-control" required="" name="link" value="{{ old('link') }}">
              <div class="invalid-feedback">
                Link Required
              </div>
            </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" accept="image/*" onchange="newFile(event)" name="image" id="image" required class="form-control">
              <div class="invalid-feedback">
                Image Required
              </div>
              <br>
              <img style="width:100%" src="" id="output"/>
              <script>
                var newFile = function(event) {
                    var newfile = document.getElementById('output');
                    newfile.src = URL.createObjectURL(event.target.files[0]);
                };
              </script>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" required="" name="status">
                <option value="active">Active</option>
                <option value="inactive" selected="selected">Inactive</option>
              </select>
              <div class="invalid-feedback">
                Status Required
              </div>
            </div>
            <div class="form-group">
              <label>Layout</label>
              <select class="form-control" required="" name="showOnHomepage">
                <option value="2">2</option>
                <option value="1" selected="selected">1</option>
              </select>
              <div class="invalid-feedback">
                Layout Required
              </div>
            </div>
            <div class="form-group">
              <label>Priority</label>
              <input type="number" class="form-control" required="" name="priority" value="{{ old('priority') }}">
              <div class="invalid-feedback">
                Priority Required
              </div>
            </div>
            <div class="form-group">
              <label>Browser Title</label>
              <input type="text" class="form-control" required="" name="browserTitle" value="{{ old('browserTitle') }}">
              <div class="invalid-feedback">
              Browser Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Meta Description</label>
                <textarea id="metaDec" class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}"></textarea>
              <div class="invalid-feedback">
              Meta Description Required
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div> 


<!-- modal -->
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $(":input").not("[name=menuId]")
        .prop("disabled", true);
    $('#metaDec').summernote('disable');
    $('#dec').summernote('disable');
});

function cekMenu(){
    $(":input").not("[name=menuId]")
        .prop("disabled", false);
    $('#metaDec').summernote('enable');
    $('#dec').summernote('enable');
}
</script>
@endpush

@push('scripts')
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
@endpush