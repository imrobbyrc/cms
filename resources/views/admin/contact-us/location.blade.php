@extends('admin.main')
​
@section('title')
    <title>header content</title>
@endsection
​
@push('css') 
<style>
.mapouter{position:relative;text-align:right;height:500px;width:600px;}
.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:600px;}
</style> 
@endpush

@section('content') 
<div class="section">
  <div class="section-header">
      <h1>Location</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item"><a href="#">Homepage</a></div>
        <div class="breadcrumb-item">Location</div>
      </div>
  </div>  

  <div class="card">
      <div class="card-header">
        <h4>Manage Contact Us Location</h4>
      </div>
      <div class="card-body">
        <div class="row">
            <div class="col-7">
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=Kompleks%20Ruko%20Roxy%20Mas%2C%20Jalan%20Roxy%20Mas%20Pertokoan%2C%20RW.8%2C%20Cideng%2C%20Central%20Jakarta%20City%2C%20Jakarta&t=&z=19&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <label for="map">Masukkan Alamat</label>
                <input onchange="changemap()" id="map" type="text" class="form-control">
                <button class="btn btn-primary btn-block mt-2">Simpan</button>
            </div>
        </div>
      </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
    function changemap(){
        var url = $('#map').val();
        var encodedUrl = encodeURI(url);
        $('#gmap_canvas').attr('src', 'https://maps.google.com/maps?q='+encodedUrl+'&t=&z=19&ie=UTF8&iwloc=&output=embed')
    }
</script>
@endpush