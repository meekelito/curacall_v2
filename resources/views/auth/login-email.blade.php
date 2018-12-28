<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Global stylesheets --> 
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>  
  <script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script>
  <!-- /theme JS files -->
  <style type="text/css">
    body{
      background: #002b58 !important;
    }
    .login-form{
      padding: 40px 30px 20px !important;
      width: 360px !important;
    }

  </style>

</head>

<body class="login-container bg-slate-800">

  <!-- Page container -->
  <div class="page-container">

    <!-- Page content -->
    <div class="page-content">

      <!-- Main content -->
      <div class="content-wrapper">

        <!-- Content area -->
        <div class="content">

          <!-- Advanced login -->
          <form method="POST" action="{{ url('login/email') }}">
            {{ csrf_field() }}
            <div class="panel panel-body login-form"> 
              <div class="text-center">
                <img src="{{ asset('assets/images/curacall_logo.jpg') }}" width="100">
                <h5 class="content-group-lg">Login to your account <small class="display-block">Enter your Username / Email</small></h5>
              </div>

              <div class="form-group has-feedback has-feedback-left">
                <input type="text" class="form-control input-lg" name="email" @if(Session::has('login_email')) value="{{ Session::get('login_email') }}" @endif placeholder="Email" required autofocus>
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>
                @if ($message = Session::get('warning'))
                    <span class="help-block text-warning-400"><i class="icon-cancel-circle2 position-left"></i>{{ $message }}</span>
                @endif
              </div>
                            
              <div class="form-group login-options">
                <div class="row">
                  <div class="col-sm-6">
                    <a >Forgot email?</a>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn bg-blue btn-block">Next <i class="icon-circle-right2 position-right"></i></button>
              </div>


            </div>
          </form>
          <!-- /advanced login -->

        </div>
        <!-- /content area -->

      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->

</body>
 
</html>