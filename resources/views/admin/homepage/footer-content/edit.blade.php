@extends('admin.main')

@section('title')
    <title>Edit Footer Content</title>
@endsection

@section('content')
<div class="section">
  <div class="section-header">
      <h1>Footer Content</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Homepage</a></div>
        <div class="breadcrumb-item">Footer Content</div>
      </div>
  </div>
  <div class="card">
      <div class="card-header">
        <h4>Manage Footer Content</h4>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('homepage.update',request()->segment(3))}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" required="" name="idUpdate" value="{{ $data->idFooter}}" readonly>
      <div class="card-body">
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
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="title" value="{{ $data->title}}" required>
            <div class="invalid-feedback">
                Title Required
            </div>
          </div>
        </div>
        <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Priority</label>
            <div class="col-sm-12 col-md-7">
                <input type="number" class="form-control" required="" name="priority" value="{{ $data->priority}}" required>
                <div class="invalid-feedback">
                Priority Required
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
            <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="status">
                    <option value="active" @if($data->status == 'active') selected @endif>Active</option>
                    <option value="inactive" selected="selected">Inactive</option>
                </select>
                <div class="invalid-feedback">
                    Status Required
                </div>
            </div>
        </div>
        <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Descriptions</label>
            <div class="col-sm-12 col-md-7">
                <textarea class="summernote" name="descriptions" required>{{ $data->description }}</textarea>
                <div class="invalid-feedback">
                    Description Required
                </div>
            </div>
          </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary">Publish</button>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div> 
@endsection