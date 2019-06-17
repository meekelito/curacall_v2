  <!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
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
  <script  src="{{ asset('js/notification.js') }}" type="text/javascript" defer></script> 
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/drilldown.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/bower_components/jquery-pjax/jquery.pjax.js') }}"></script>

	<!-- /core JS files --> 

	<!-- Theme JS files -->   
  <!-- <script src="{{ asset('js/theme.js') }}"></script>  -->
  <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/core/libraries/jasny_bootstrap.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/inputs/formatter.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>

  <script type="text/javascript" src="{{ asset('js/favico-0.3.10.min.js') }}"></script> 
  <script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/switch.min.js') }}" ></script> 
  <script type="text/javascript" src="{{ asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
  
	<script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script> 
  <script src="{{ asset('js/jquery.getscripts.min.js') }}"></script>

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
    }

    .navbar-brand img {
      margin-top: -8px;
      height: 35px;
    }

    .navbar{
      border-top: none;
    }

    body.wait-pointer * {cursor: wait !important;}
    .page-container{
      margin-top: 47px;
    }
    
    .badge-notif{
      background-color:#F44336 !important;position: relative;top:-10px;right:10px;
      height: 15px;
      width: 15px;
      border-radius: 50%;
      display: inline-block;
      position:relative;
      top:-5px;
    }
  </style>
</head>

<body class="sidebar-xs">
	<!-- Main navbar -->
	<div class="navbar navbar-inverse" style="position: fixed;
      top: 0;width: 100%;z-index: 999;">
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

  <div id="notificationapp">
     <audio id="caseNotificationAudio">
      <source src="{{ asset('assets/notification/sounds/facebook_notif.mp3') }}" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>

    <audio id="chatNotificationAudio">
      <source src="{{ asset('assets/notification/sounds/facebook_chat_2016.mp3') }}" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>

    <audio id="reminderNotificationAudio">
      <source src="{{ asset('assets/notification/sounds/reminder.mp3') }}" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>

		<div class="navbar-collapse collapse" id="navbar-mobile"> 
			<ul class="nav navbar-nav">
        <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-transmission"></i></a></li>
         <chatnotification v-bind:chatnotifications="chatnotifications"></chatnotification>
         <notification v-bind:notifications="notifications"></notification>
         <remindernotification v-bind:remindernotifications="remindernotifications"></remindernotification>
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
                {{ ucwords(Auth::user()->fname) }}, {{ Auth::user()->role->role_title }}
              </span>

							<i class="caret"></i>
						</a>
            @endif

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="{{ url('user-account-settings') }}"><i class="icon-cog5"></i> Account settings</a></li>
              <li><a><i class="icon-question3"></i> Quick Help</a></li>
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
                  <img src="{{ asset('storage/uploads/users/'.Auth::user()->prof_img.'?v='.strtotime('now')) }}" class="img-circle img-sm" alt=""></a>
                  @else
                  <img src="{{ asset('storage/uploads/users/default.png') }}" class="img-circle img-sm"  alt=""></a>
                  @endif
								<div class="media-body">
									<span class="media-heading text-semibold">
                    @if(Auth::user())
                      {{ ucwords(Auth::user()->fname." ".Auth::user()->lname) }}
                    @endif
                  </span>
									<div class="text-size-mini text-muted">
										<i class="icon-briefcase text-size-small"></i> &nbsp; {{ Auth::user()->role->role_title }} 
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="{{ url('user-account-settings') }}"><i class="icon-cog3"></i></a>
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
                @if(auth()->user()->hasAnyPermission([    
                    'view-account-reports',                  
                    'view-oncall-reports']))
                    <li  class="menu-dashboard"><a href="{{ url('/dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                @endif
         
                 
                  @if(auth()->user()->hasAnyPermission([    
                    'view-all-cases',                  
                    'view-active-cases', 
                    'view-pending-cases',
                    'view-closed-cases']))
                   <li class="menu-cases"><a href="{{ url('/all-cases') }}"><i class="icon-briefcase"></i> <span>Cases</span></a></li>
                  @endif
                 
                  @can('send-message-to-anyone')
                  <li class="menu-messages"><a href="{{ url('/new-message') }}"><i class="icon-bubbles4"></i> <span>Messages</span></a></li>
                  @endcan

                  @can('view-contacts')
                  <li class="menu-contacts"><a href="{{ url('/contacts') }}"><i class="icon-address-book2"></i> <span>Contacts</span></a></li>
                  @endcan
                  <!-- <li class="menu-directory"><a href="{{ url('/directory') }}"><i class="icon-book3"></i> <span>Directory</span></a></li> -->
                  @if(auth()->user()->hasAnyPermission([    
                    'profile-setting',                  
                    'security-login', 
                    'message-setting',
                    'notification-setting']))
                  <li class="menu-user-account-settings"><a href="{{ url('/user-account-settings') }}"><i class="icon-gear"></i> <span>Settings</span></a></li>
                  @endif

                
                  @can('case-repository')
                    <li class="menu-repository-cases"><a href="{{ url('/repository-cases') }}"><i class="icon-file-check2"></i> <span>Case Repository</span></a></li>
                  @endcan

                  @can('archived-closed-case')
                    <li class="menu-archive-cases"><a href="{{ url('/archived-cases') }}"><i class="icon-bin"></i> <span>Archive Closed Cases</span></a></li>
                  @endcan

                  @hasrole('curacall-admin')
                    <li class="navigation-header"><span>Admin Console</span> <i class="icon-menu" title="Admin Console"></i></li>
                  @endhasrole

                  @can('manage-curacall-general-info')
                    <li class="menu-admin-console-general"><a href="{{ url('/admin-console/general') }}"><i class="icon-hammer-wrench"></i> <span>General Information</span></a></li>
                  @endcan
                <!--     <li class="menu-admin-console-roles"><a href="{{ url('/admin-console/roles') }}"><i class="icon-share3"></i> <span>Roles</span></a></li> -->
                  @can('manage-roles')
                    <li class="menu-admin-console-roles"><a href="{{ url('/admin/roles') }}"><i class="icon-key"></i> <span>Role & Access Control List</span></a></li>
                  @endcan

                  @can('manage-users')
                    <li class="menu-admin-console-users"><a href="{{ url('/admin-console/users') }}"><i class="icon-users"></i> <span>Users</span></a></li>
                  @endcan

                  @can('manage-accounts')
                    <li class="menu-admin-console-accounts"><a href="{{ url('/admin-console/accounts') }}"><i class="icon-office"></i> <span>Accounts</span></a></li>
                  @endcan

                  @can('manage-billing')
                    <li class="menu-admin-console-billing"><a href="{{ url('/admin-console/billing') }}"><i class="icon-coins"></i> <span>Billing</span></a></li>
                    <li class="menu-admin-console-reports"><a href="{{ url('/admin-console/reports') }}"><i class="icon-graph"></i> <span>Reports</span></a></li>
                  @endif

                  @hasrole('account-admin')
                  <li class="navigation-header"><span>Admin Console</span> <i class="icon-menu" title="Admin Console"></i></li>
                  @endhasrole

                  @can('manage-account-general-info')

                    <li class="menu-account-general-info"><a href="{{ url('/account/general-info') }}"><i class="icon-hammer-wrench"></i> <span>General Information</span></a></li>
                  @endcan

                  @can('case-management')
                    <li class="menu-account-case-management"><a href="{{ url('account/case-management') }}"><i class="icon-files-empty2"></i> <span>Case Management</span></a></li>
                  @endcan
                
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
            <!-- Sub navigation -->
            <div class="sidebar-category">
                <div class="category-title" align="center">
                  <span style="font-size: 16px;">
                    @if(empty(Auth::user()->account->account_name))
                    CuraCall
                    @else
                    {{ Auth::user()->account->account_name }}
                    @endif
                  </span>
                </div>
                <div class="category-title">
                    <span>Cases</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content no-padding">
                    <ul class="navigation navigation-alt navigation-accordion submenu-curacall">
                      @can('view-all-cases')
                        <li class="submenu-cases-all-cases"><a href="{{ url('/all-cases') }}"><i class="icon-files-empty"></i> All cases <span class="badge badge-default" id="case-count-all">0</span></a></li> 
                      @endcan

                      @can('view-active-cases')
                        <li class="submenu-cases-active-cases"><a href="{{ url('/active-cases') }}"><i class="icon-file-plus"></i> Active cases <span class="badge badge-danger" style="background-color: #03a9f4; border-color: #03a9f4;" id="case-count-active">0</span></a></li> 
                      @endcan

                      @can('view-pending-cases')
                        <li class="submenu-cases-pending-cases"><a href="{{ url('/pending-cases') }}"><i class="icon-hour-glass"></i> Pending cases <span class="badge badge-warning" style="background-color: #f44336; border-color: #f44336;" id="case-count-pending">0</span></a></li>
                      @endcan

                      @can('view-closed-cases')
                        <li class="submenu-cases-closed-cases"><a href="{{ url('/closed-cases') }}"><i class="icon-file-locked"></i> Closed cases <span class="badge badge-warning" style="background-color: #4caf50; border-color: #4caf50;" id="case-count-closed">0</span></a></li>
                      @endcan

                      @can('view-silent-cases')
                        <li class="submenu-cases-silent-cases"><a href="{{ url('/silent-cases') }}"><i class="icon-volume-mute5"></i> Silent cases <span class="badge badge-warning" style="background-color: #90A4AE; border-color: #90A4AE;" id="case-count-silent">0</span></a></li>
                      @endcan
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

  <script type="text/javascript">
    $(function() {
      $(document).on('pjax:popstate', function() {
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

      var notification_count = 0;
      var favicon = new Favico({
          animation : 'popFade',
          position : 'up'
      });
      
      //intial value
      favicon.badge(notification_count);

    });
    count_case();
    function count_case(){
      $.ajax({
        type: "POST",
        url: "{{ url('count-case') }}",
        data: { 
          _token : '{{ csrf_token() }}'
        },
        success: function (data) {
          var res = $.parseJSON(data);
          if( res.status == 1 ){
            $("#case-count-all").html(res.all_count);
            $("#case-count-active").html(res.active_count);
            $("#case-count-pending").html(res.pending_count);
            $("#case-count-closed").html(res.closed_count); 
            $("#case-count-silent").html(res.silent_count); 
          }
        },
        error: function (data){
          alert("No connection could be made because the target machine actively refused it. Please refresh the browser and try again.");
        }
      });
    }

    function showLoader(){
      $("#content").block({
        message: '<i style="font-size: 30px;" class="icon-spinner2 spinner"></i>',
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
      'baseUrl' => url('/')
    ]) !!};

      setInterval(function(){ 

        $.get("{{ route('checkuser') }}?id="+$('meta[name="userId"]').attr('content'), function(data,status){
             if(data == 0)
             {
                swal({
                  title: "Your session expired",
                  text: "You may had been idle. Please login again",
                  type: "info",
                  //timer: 2000,
                  showConfirmButton: true,
                  confirmButtonText: "Login"
                }, function(){
                      window.location.href = "{{ url('/') }}";
                });
             }
              console.log(data + ' ' + status);
          }).fail(function() {
              swal({
                  title: "Your session expired",
                  text: "You may had been idle. Please login again",
                  type: "info",
                  //timer: 2000,
                  showConfirmButton: true,
                  confirmButtonText: "Login"
                }, function(){
                      window.location.href = "{{ url('/') }}";
              });
         });

      }, 8000);
  </script>
</html>



