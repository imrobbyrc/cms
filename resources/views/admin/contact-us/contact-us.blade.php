@extends('admin.main')

@section('title')
    <title>Contact Us</title>
@endsection

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
    <form class="needs-validation" novalidate="" method="post" action="{{ route('contact-us.store',request()->segment(3))}}" enctype="multipart/form-data">
        @csrf
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
        <div class="text-right my-2">
            <button class="btn btn-primary">Simpan <i class="far fa-save"></i></button>
        </div>
        <input type="hidden" class="form-control" required="" name="idUpdate" value="{{$data->idContacts ?? ''}}" readonly>
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
                    <textarea class="summernote" name="contact" required>{{$data->contact ?? ''}}</textarea>
                </div>
                <div class="invalid-feedback">
                    Contact Page Required
                </div>
                
                <div class="col-3 my-2"> 
                    Show On Homepage ?
                </div>
                <div class="col-9 my-2"> 
                    <select class="form-control" required="" name="showOnHomepage">
                    <option value="yes" @if(isset($data->showOnHomepage) && $data->showOnHomepage == 'yes' ) selected @endif>Yes</option>
                    <option value="no" @if(isset($data->showOnHomepage) && $data->showOnHomepage == 'no' ) selected @endif>No</option>
                    </select>
                </div>
                <div class="invalid-feedback">
                    Required
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
                    <input type="text" class="form-control" name="serviceTitle{{$i}}" 
                    value="{{ $data->{'serviceTittle'.$i} ?? '' }}">
                    </div>

                    <div class="col-3 my-2"> 
                        Service Description
                    </div>
                    <div class="col-9 my-2"> 
                        <textarea class="summernote" name="serviceDescription{{$i}}">{{ $data->{'serviceDescription'.$i} ?? ''}}</textarea>
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
                    <input type="text" class="form-control" name="browserTitle" value="{{$data->browserTitle ?? ''}}" required>
                </div>
                <div class="invalid-feedback">
                    Browser Title Required
                </div>

                <div class="col-3 my-2"> 
                    Meta Description
                </div>
                <div class="col-9 my-2"> 
                    <textarea class="summernote" name="metaDescription" required>{{$data->metaDescription ?? ''}}</textarea>
                </div>
                <div class="invalid-feedback">
                    Meta Description Required
                </div>

            </div>
            </div> 
        </div> 

        <button class="btn btn-primary btn-block">Simpan</button>
    </form>
</div>


@endsection