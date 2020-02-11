<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Carbon | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="{{ asset('admin_assets/modules/bootstrap/css/bootstrap.min.css')}}">

  <link rel="stylesheet" href=".{{ asset('admin_assets/modules/fontawesome/css/all.min.css')}}">

  <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css')}}">

  <link rel="stylesheet" href="{{ asset('admin_assets/css/components.css')}}">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              {{-- <img src="{{asset('admin_assets/img/logo.png')}}" alt="logo" width="100" class="shadow-light rounded-circle"> --}}
              LOGO
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>
              <!-- alert-->
              @if(\Session::has('alert'))
              <div class="alert alert-danger">
                  <div>{{Session::get('alert')}}</div>
              </div>
              @endif
              @if(\Session::has('alert-success'))
              <div class="alert alert-success">
                  <div>{{Session::get('alert-success')}}</div>
              </div>
              @endif
              <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="@if(isset($_COOKIE['email'])) {{$_COOKIE['email']}} @endif" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                    	<label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="@if(isset($_COOKIE['password'])){{$_COOKIE['password']}}@endif" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" @if(isset($_COOKIE['email'])) checked @endif>
                      <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <div class="simple-footer">
              Copyright &copy; Carbon 2020
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
    <!-- /.login-box -->
    
 <!-- jQuery 3 -->

 <script src="{{asset('admin_assets/modules/jquery.min.js')}}"></script>
 <script src="{{asset('admin_assets/modules/popper.js')}}"></script>
 <script src="{{asset('admin_assets/modules/tooltip.js')}}"></script>
 <script src="{{asset('admin_assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
 <script src="{{asset('admin_assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
 <script src="{{asset('admin_assets/modules/moment.min.js')}}"></script>
 <script src="{{asset('admin_assets/js/stisla.js')}}"></script>
 
  </body>
</html>
