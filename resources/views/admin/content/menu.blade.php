@extends('admin.main')
@section('title')
    <title>Menu</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Menu</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Menu</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Menu List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="menu">
          <thead>
            <tr>
              <th>#</th>
              <th>Menu</th>
              <th>Link</th>
              <th>Status</th>
              <th>Show On Homepage</th>
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
              <input type="text" class="form-control" required="" name="menu" value="{{ old('menu') }}">
              <div class="invalid-feedback">
                Menu Required
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
              <label>Show on homepage</label>
              <select class="form-control" required="" name="showOnHomepage">
                <option value="yes">yes</option>
                <option value="no" selected="selected">no</option>
              </select>
              <div class="invalid-feedback">
                Show on homepage Required
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
                <textarea class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}"></textarea>
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
</script>
@endpush

@push('scripts')
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
@endpush