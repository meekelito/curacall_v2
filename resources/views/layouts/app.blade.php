<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>CuraCall</title>
  <link rel="shortcut icon" href="{{ asset('assets/images/curacall-ico.png') }}" />

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <!-- <link href="{{ asset('css/all.css') }}" rel="stylesheet" type="text/css"> -->
	<link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
  <!-- <script src="{{ asset('js/core.js') }}"></script> --> 
  <!-- <script  src="{{ asset('js/app.js') }}" type="text/javascript" defer></script>  -->
	<!-- <script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}" ></script> -->
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}" ></script>
	<!-- /core JS files --> 

	<!-- Theme JS files -->   
  <!-- <script src="{{ asset('js/theme.js') }}"></script>  -->
  <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jasny_bootstrap.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/inputs/formatter.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/switch.min.js') }}" ></script> 
	<script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script> 

	<!-- /theme JS files -->
	<style type="text/css">
	 	.navbar-inverse {
	    background-color: #0094BA;
	    border-color: #0094BA;
	  }
	  .sidebar-main{
	    background-color: #035B70;
	  }
    .page-container{
      min-height: 910px !important;
    }
    .navigation li ul{
      background-color: #035B70 !important;
    } 

    .navbar-header{
      background-color: #fff;
      /*min-width: 315px;*/
    }

    .navbar-brand img {
      margin-top: -8px;
      height: 35px;
    }

    .navbar{
      border-top: none;
    }
    
  </style>
</head>

<body class="sidebar-xs">
  
	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<!-- <a class="navbar-brand" href="#" style="font-size: 26px;">CuraCall
      </a> --> 
      <a class="navbar-brand" href="#"><img src="{{ asset('assets/images/curacall.png') }}" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
				<li><a class="sidebar-mobile-secondary-toggle"><i class="icon-more"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile"> 
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
				<li><a class="sidebar-control sidebar-secondary-hide hidden-xs hide-this-ass"><i class="icon-transmission"></i></a></li>
			</ul>

			<p class="navbar-text">
				<span class="label bg-success">Online</span>
			</p>

			<div class="navbar-right">
				<ul class="nav navbar-nav">
		      <li><a>Features</a></li>
          <li><a>About us</a></li>
          <li><a>Contact us</a></li>
					<li class="dropdown dropdown-user">
            @if(Auth::user())
						<a class="dropdown-toggle" data-toggle="dropdown">
              @if( file_exists('storage/uploads/users/'.Auth::user()->prof_img) )
                <img src="{{ asset('storage/uploads/users/'.Auth::user()->prof_img.'?v='.strtotime('now')) }}" alt="">
              @else
                <img src="{{ asset('storage/uploads/users/default.png') }}" alt="">
              @endif

							<span>
                {{ ucwords(Auth::user()->fname) }}
              </span>
							<i class="caret"></i>
						</a>
            @endif

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="{{ url('user-account-settings') }}"><i class="icon-cog5"></i> Account settings</a></li>
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"><i class="icon-switch2"></i>
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page containers -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left">
                  @if( file_exists('storage/uploads/users/'.Auth::user()->prof_img) )
                  <img src="{{ asset('storage/uploads/users/'.Auth::user()->prof_img.'?v='.strtotime('now')) }}" class="img-circle img-sm" alt="">
                  @else
                  <img src="{{ asset('storage/uploads/users/default.png') }}" class="img-circle img-sm"  alt="">
                  @endif
								<div class="media-body">
									<span class="media-heading text-semibold">
                    @if(Auth::user())
                      {{ ucwords(Auth::user()->fname." ".Auth::user()->lname) }}
                    @endif
                  </span>
									<div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp;Santa Ana, CA
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="#"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->

					<!-- Main navigation -->
          <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
              <ul class="navigation navigation-main navigation-accordion menu-curacall">
                <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
                <li  class="menu-dashboard"><a href="{{ url('/dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                @if( Auth::user() ) 
                  <li class="menu-cases"><a href="{{ url('/all-cases') }}"><i class="icon-bubbles4"></i> <span>Cases</span></a></li>
                  <li class="menu-contacts"><a href="{{ url('/contacts') }}"><i class="icon-address-book2"></i> <span>Contacts</span></a></li>
                  <li class="menu-directory"><a href="{{ url('/directory') }}"><i class="icon-book3"></i> <span>Directory</span></a></li>
                  <!-- <li class="menu-broadcast"><a href="{{ url('/broadcast') }}"><i class="icon-megaphone"></i> <span>Broadcast</span></a></li> -->
                  <li class="menu-user-account-settings"><a href="{{ url('/user-account-settings') }}"><i class="icon-gear"></i> <span>Account Settings</span></a></li>
                  <li class="menu-archive-messages"><a href="{{ url('/archived-messages') }}"><i class="icon-bin"></i> <span>Archive Closed Messages</span></a></li>
                  @if( Auth::user()->role_id == 1 )
                    <li class="navigation-header"><span>Admin Console</span> <i class="icon-menu" title="Admin Console"></i></li>
                    <li class="menu-admin-console-general"><a href="{{ url('/admin-console/general') }}"><i class="icon-hammer-wrench"></i> <span>General Information</span></a></li>
                    <li class="menu-admin-console-roles"><a href="{{ url('/admin-console/roles') }}"><i class="icon-share3"></i> <span>Roles</span></a></li>
                    <li class="menu-admin-console-users"><a href="{{ url('/admin-console/users') }}"><i class="icon-users"></i> <span>Users</span></a></li>
                    <li class="menu-admin-console-accounts"><a href="{{ url('/admin-console/accounts') }}"><i class="icon-office"></i> <span>Accounts</span></a></li>
                  @endif
                  @if( Auth::user()->role_id == 4 )
                    <li class="navigation-header"><span>Admin Console</span> <i class="icon-menu" title="Admin Console"></i></li>
                    <li class="menu-account-general-info"><a href="{{ url('/account/general-info') }}"><i class="icon-hammer-wrench"></i> <span>General Information</span></a></li>
                    <li class="menu-account-roles"><a href="{{ url('/account/roles') }}"><i class="icon-share3"></i> <span>Roles</span></a></li>
                  @endif
                @endif
              </ul>
            </div>
          </div>
          <!-- /main navigation -->
				</div>
			</div>
			<!-- /main sidebar -->


			<!-- Secondary sidebar -->
      <div class="sidebar sidebar-secondary sidebar-default">
          <div class="sidebar-content">
            <!-- Actions -->
            <div class="sidebar-category">
              <div class="category-title">
                <span>Action</span>
                <ul class="icons-list">
                  <li><a href="#" data-action="collapse"></a></li>
                </ul>
              </div>

              <div class="category-content"> 
                <a href="{{ url('/new-message') }}" class="btn bg-primary btn-rounded btn-block btn-xs">New message</a>
              </div>
            </div>
            <!-- /actions -->

            <!-- Sub navigation -->
            <div class="sidebar-category">
                <div class="category-title">
                    <span>Cases</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content no-padding">
                    <ul class="navigation navigation-alt navigation-accordion submenu-curacall">
                        <li class="submenu-cases-all-cases"><a href="{{ url('/all-cases') }}"><i class="icon-files-empty"></i> All cases <!-- <span class="badge badge-default">5</span> --></a></li> 
                        <li class="submenu-cases-active-cases"><a href="{{ url('/active-cases') }}"><i class="icon-file-plus"></i> Active cases <!-- <span class="badge badge-danger">3</span> --></a></li> 
                        <li class="submenu-cases-pending-cases"><a href="{{ url('/pending-cases') }}"><i class="icon-hour-glass"></i> Pending cases <!-- <span class="badge badge-warning" style="background-color: #E4D00A; border-color:#E4D00A;">2</span> --></a></li>
                        <li class="submenu-cases-closed-cases"><a href="{{ url('/closed-cases') }}"><i class="icon-file-locked"></i> Closed cases</a></li>
                        <li class="submenu-cases-deleted-cases"><a href="{{ url('/deleted-cases') }}"><i class="icon-bin"></i> Deleted cases</a></li>
                    </ul>
                </div>
            </div>
            <!-- /sub navigation -->
          </div>
      </div>
      <!-- /secondary sidebar -->

			<!-- Main content -->
			<div class="content-wrapper" id="content">
        @yield('css')   
        <div id="app">
        @yield('content')
        </div>
        @yield('script') 
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
    
</body>
<script src="{{ asset('js/moment.min.js') }}"></script>
  <script type="text/javascript">
    $(function() {
      $(document).on('pjaxopstate', function() {
        location.reload();
      }); 

      $(document).pjax('a', '#content');
      
      $(document).on('pjax:timeout', function(event) {
        event.preventDefault()
      });
      
      $(document).on('pjax:send', function(event) {
        showLoader();
      });

      $(document).on('pjax:complete', function(event) {
        hideLoader();
      });

    });

    function showLoader(){
      $("#content").block({
        message: '<i style="font-size: 50px;" class="icon-spinner spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
      });
    }

    function hideLoader(){
      $("#content").unblock();
    }
    
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      'user' => Auth::user(),
      'pusherKey' => config('broadcasting.connections.pusher.key'),
    ]) !!};
  </script>
</html>



