<!DOCTYPE html>
<html lang="en">
<head>
  
  @yield('title')
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/fontawesome/css/all.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/datatables/datatables.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/bootstrap-daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/css/components.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
  @stack('css')
  <link rel="shortcut icon" type="image/png" href="{{asset ('img/favicon.png')}}"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      @include('admin.partials.header')
      

      @include('admin.partials.sidebar')

      <!-- Main Content -->
      <div class="main-content">
          @yield('content')
      </div>

      <!-- footer was here-->
      @include('admin.partials.footer')
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="{{ asset('admin_assets/modules/jquery.min.js')}}"></script>
  {{-- <script src="{{ asset('admin_assets/js/jquery-ui.min.js')}}"></script> --}}
  <script src="{{ asset('admin_assets/modules/popper.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/tooltip.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/moment.min.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/select2/dist/js/select2.js')}}"></script>
  <script src="{{ asset('admin_assets/js/stisla.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/datatables/datatables.min.js')}}"></script>
  <script src="{{ asset('admin_assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.j')}}s"></script>
  <script src="{{ asset('admin_assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')}}"></script>
  <script src="{{ asset('admin_assets/js/fnSetFilteringDelay.js') }}"></script>
  <script src="{{ asset('admin_assets/js/scripts.js')}}"></script>
  <script src="{{ asset('admin_assets/js/custom.js')}}"></script>
  <script src="{{ asset('admin_assets/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
  <script> //images validation
    var _URL = window.URL || window.webkitURL;
    function image_validation(file,preview,inputFile,dimension)
    {
      var img;
      if ((file)) {
            img = new Image();

            img.onload = function() {
                if(this.width === dimension.width && this.height === dimension.height ){
                  var reader = new FileReader();
                  reader.readAsDataURL(file); 
                  reader.onloadend = function() {
                    preview.attr("src", reader.result);
                        
                  }

                }else{
                  
                  inputFile.val("");
                  preview.attr("src", '');
                  Swal.fire("Failed!", "Image size must be "+ dimension.width +" x "+ dimension.height +" pixel.", "warning");
                }
            };
            img.src = _URL.createObjectURL(file);
        } 
    }
  </script>
@include('sweet::alert')
@stack('scripts')
</body>
</html>
