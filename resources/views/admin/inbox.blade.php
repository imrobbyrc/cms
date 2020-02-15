@extends('admin.main')
@section('title')
    <title>Message Inbox</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Message Inbox</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Message Inbox</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Message Inbox List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#detailModal">Message Detail</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="inbox">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Message</th>
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
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
        <div class="modal-body"> 
          <div class="card-body">
            <div class="form-group">
              <label>Name</label>
              <input disabled type="text" class="form-control" name="name">
            </div>
            <div class="form-group">
              <label>Email</label>
                <input disabled type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
              <label>Phone</label>
                <input disabled type="number" class="form-control" name="phone">
            </div>
            <div class="form-group">
              <label>Message</label>
                <textarea id="dec" class="summernote-simple" name="message" value=""></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
        </div>
      </form>
    </div>
  </div>
</div> 


<!-- modal -->
@endsection

@push('scripts')
<script>
$( document ).ready(function() {
  $('#dec').summernote('disable');
});
</script>
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
@endpush