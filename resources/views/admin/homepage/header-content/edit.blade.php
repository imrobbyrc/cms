@extends('admin.main')

@section('title')
    <title>Header Content</title>
@endsection

@section('content')
<div class="section">
  <div class="section-header">
      <h1>Header Content</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Homepage</a></div>
        <div class="breadcrumb-item">Header Content</div>
      </div>
  </div>
  <div class="card">
      <div class="card-header">
        <h4>Edit Header Content</h4>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('homepage.update',request()->segment(3))}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" required="" name="idUpdate" value="{{ $data->idHeader}}" readonly>
        <input type="hidden" class="form-control" required="" name="currentbrowserIcon" value="{{ $data->browserIcon}}"  readonly>
        <input type="hidden" class="form-control" required="" name="currentheaderLogo" value="{{ $data->headerLogo}}"  readonly>
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
            <input type="text" class="form-control" name="title" value="{{ $data->browserTitle}}" required>
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Right Content</label>
          <div class="col-sm-12 col-md-7">
            <textarea class="summernote" name="rightContent" required>{{ $data->contentRight}}</textarea>
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Left Content</label>
          <div class="col-sm-12 col-md-7">
            <textarea class="summernote" name="leftContent" required>{{ $data->contentLeft}}</textarea>
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Descriptions</label>
          <div class="col-sm-12 col-md-7">
            <textarea class="summernote" name="descriptions" required>{{ $data->metaDescription}}</textarea>
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Icon</label>
          <div class="col-sm-12 col-md-6">
            <input type="file" accept="image/*" name="imageIcon" class="form-control imagesIcon">
              <br>
              <img id="output"  src="{{asset($data->browserIcon)}}" class="images outputIcon" style="width: 20%;"/>
          </div>
          
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Header Logo</label>
          <div class="col-sm-12 col-md-6">
           
              <input type="file" accept="image/*" name="imageLogo" class="form-control imagesHeader">
              <br>
              <img id="output" src="{{asset($data->headerLogo)}}" class="images outputHeader" style="width: 20%;"/>
            
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary">Update</button>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div> 
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/modules/upload-preview/assets/js/jquery.uploadPreview.min.js')}}"></script>

<script>
$(".imagesHeader").change(function(e) {
    var file = this.files[0];
    var preview = $(".outputHeader");
    var inputFile = $(".imagesHeader");
    var dimension = [];
        dimension['width'] = 400;
        dimension['height'] = 100;
    image_validation(file,preview,inputFile,dimension)

});
$(".imagesIcon").change(function(e) {
    var file = this.files[0];
    var preview = $(".outputIcon");
    var inputFile = $(".imagesIcon");
    var dimension = [];
        dimension['width'] = 200;
        dimension['height'] = 200;
    image_validation(file,preview,inputFile,dimension)

});
</script>
@endpush