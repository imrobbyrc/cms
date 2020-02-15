@extends('admin.main')
​
@section('title')
    <title>footer content</title>
@endsection
​
@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')

<div class="section">
  <div class="section-header">
      <h1>Contact Us</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Homepage</a></div>
        <div class="breadcrumb-item">Contact Us</div>
      </div>
  </div>  

  <div class="text-right my-2">
    <button class="btn btn-primary">Simpan <i class="far fa-save"></i></button>
  </div>
    <div class="card">
        <div class="card-header">
        <h4>Manage Contact Us</h4>
        </div>
        <div class="card-body">
        <div class="row">
            <div class="col-3 my-2"> 
                Contact Page
            </div>
            <div class="col-9 my-2"> 
                <textarea class="summernote-simple" name="contact" required></textarea>
            </div>
            <div class="col-3 my-2"> 
                Show On Homepage ?
            </div>
            <div class="col-9 my-2"> 
              <select class="form-control" required="" name="showOnHomepage">
                <option value="yes">yes</option>
                <option value="no" selected="selected">no</option>
              </select>
            </div>
        </div>
        </div> 
    </div> 

    @for ($i = 1; $i <= 3; $i++)
    <div class="card">
        <div class="card-header">
        <h4>Manage Service {{$i}}</h4>
        </div>
        <div class="card-body">
        <div class="row">
            
            <div class="col-3 my-2"> 
                Service Title
            </div>
            <div class="col-9 my-2"> 
               <input type="text" class="form-control" name="serviceTitle{{$i}}">
            </div>

            <div class="col-3 my-2"> 
                Service Description
            </div>
            <div class="col-9 my-2"> 
                <textarea class="summernote-simple" name="serviceDescription{{$i}}"></textarea>
            </div>

        </div>
        </div> 
    </div> 
    @endfor

    <div class="card">
        <div class="card-header">
        <h4>SEO Content</h4>
        </div>
        <div class="card-body">
        <div class="row">
            
            <div class="col-3 my-2"> 
                Browser Title
            </div>
            <div class="col-9 my-2"> 
               <input type="text" class="form-control" name="browserTitle">
            </div>

            <div class="col-3 my-2"> 
                Meta Description
            </div>
            <div class="col-9 my-2"> 
                <textarea class="summernote-simple" name="metaDescription"></textarea>
            </div>

        </div>
        </div> 
    </div> 

    <button class="btn btn-primary btn-block">Simpan</button>
</div>

@endsection

@push('scripts')
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>  
@endpush