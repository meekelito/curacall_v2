@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Users</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">Users</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat"> 
			<div class="panel-body">
        <div class="tabbable">
          <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="active"><a href="#highlighted-justified-tab1" data-toggle="tab">CuraCall Users</a></li>
            <li><a href="#highlighted-justified-tab2" data-toggle="tab">Client Users</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="highlighted-justified-tab1">        
              <button type="button" class="btn btn-primary btn-add-admin-user">Add New CuraCall User</button>
              <br><br>
              <table class="table tbl-admin-users" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th><th>Curacall ID</th><th>Role</th><th>First name</th><th> Last name</th><th>Email</th><th>Status</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                 <tfoot>
                  <tr>
                    <th></th><th>Curacall ID</th><th>Role</th><th>First name</th><th> Last name</th><th>Email</th><th>Status</th><th>Actions</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="tab-pane" id="highlighted-justified-tab2">
              <button type="button" class="btn btn-primary btn-add-client-user" id="curacall-client">Add New Client User</button>
              <br><br>
              <table class="table tbl-client-users" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th><th>Curacall ID</th><th>Role</th><th>First name</th><th> Last name</th><th>Email</th><th>Account</th><th>Status</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                 <tfoot>
                  <tr>
                    <th></th><th>Curacall ID</th><th>Role</th><th>First name</th><th> Last name</th><th>Email</th><th>Account</th><th>Status</th><th>Actions</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>


			</div>
		</div>
	</div>

	<div id="modal-add" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content content-data">

			</div>
		</div>
	</div>

	<div id="modal-update" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content content-data-update">

			</div>
		</div>
	</div>

  <div id="modal-update-xs" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xs">
      <div class="modal-content content-data-update">

      </div>
    </div>
  </div>


@endsection  

@section('script')

<script type="text/javascript">
	var dt_admin,dt_client;
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-admin-console-users").addClass('active');

	  dt_admin= $('.tbl-admin-users').DataTable({
      responsive: true, 
      processing: true,
      serverSide: true,
      "aaSorting": [], 
	    "language": {
	      "search": " Search : "
	    }, 
      ajax: "{{ url('admin/admin-users') }}",
      columns: [
      	{data: 'img', orderable: false, searchable: false},
        {data: 'curacall_id', name: 'users.id'},
        {data: 'role_title', name : 'b.role_title'}, 
        {data: 'fname', name : 'users.fname'}, 
        {data: 'lname', name : 'users.lname'},
        {data: 'email',name : 'users.email'},
        {data: 'status',name: 'users.status'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });

    dt_client= $('.tbl-client-users').DataTable({
      responsive: true, 
      processing: true,
      serverSide: true,
      "aaSorting": [], 
      "language": {
        "search": " Search : "
      }, 
      ajax: "{{ url('admin/client-users') }}",
      columns: [
        {data: 'img', orderable: false, searchable: false},
        {data: 'curacall_id', name: 'users.id'},
        {data: 'role_title', name : 'c.role_title'}, 
        {data: 'fname', name : 'users.fname'}, 
        {data: 'lname', name : 'users.lname'},
        {data: 'email',name : 'users.email'},
        {data: 'account_name',name : 'b.account_name'},
        {data: 'status',name: 'users.status'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });

	});

	$(".btn-add-admin-user").click(function(){
		$.ajax({ 
      type: "POST",
      url: "{{ url('admin-user-new-md') }}",
      data: { 
        _token : '{{ csrf_token() }}'
      }, 
      success: function (data) {  
        $(".content-data").html( data );
        $("#modal-add").modal('show');
      },
      error: function (data){
      	swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
      	});
      }
    });
	}); 

  $(".btn-add-client-user").click(function(){
    $.ajax({ 
      type: "POST",
      url: "{{ url('client-user-new-md') }}",
      data: { 
        _token : '{{ csrf_token() }}'
      }, 
      success: function (data) {  
        $(".content-data").html( data );
        $("#modal-add").modal('show');
      },
      error: function (data){
        swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
        });
      }
    });
  });

	function get_admin_user_md(id){
		$.ajax({ 
      type: "POST", 
      url: "{{ url('admin-user-update-md') }}",
      data: { 
        _token : '{{ csrf_token() }}',
        id: id
      },
      success: function (data) {  
        $(".content-data-update").html( data );
        $("#modal-update").modal('show');
      },
      error: function (data){
      	swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
      	});
      }
    });
	}

  function get_client_user_md(id){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('client-user-update-md') }}",
      data: { 
        _token : '{{ csrf_token() }}',
        id: id
      },
      success: function (data) {  
        $(".content-data-update").html( data );
        $("#modal-update").modal('show');
      },
      error: function (data){
        swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
        });
      }
    });
  }

  function get_status_md(id){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('update-status-md') }}",
      data: { 
        _token : '{{ csrf_token() }}',
        id: id
      },
      success: function (data) {  
        $(".content-data-update").html( data );
        $("#modal-update-xs").modal('show');
      },
      error: function (data){
        swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
        });
      }
    });
  }

  function reset_password(id){
    swal({
        title: "Are you sure?",
        text: "The user's password will be updated with a random passcode.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF5350",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm) {
        $.ajax({ 
          type: "POST", 
          url: "{{ url('admin/reset-password') }}",
          data: { 
            _token : '{{ csrf_token() }}',
            id: id
          },
          success: function (data) {  
            var res = $.parseJSON(data);
            swal({
                title: "Good job!",
                text: "The user's password is successfully updated. \n Temporary passcode : "+ res.message,
                confirmButtonColor: "#66BB6A",
                type: "success"
            });
          },
          error: function (data){
            swal({
              title: "Oops..!",
              text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
              confirmButtonColor: "#EF5350",
              type: "error"
            });
          }
        });
      }
      else {
        swal({
            title: "Cancelled",
            text: "Resetting user's password cancelled.",
            confirmButtonColor: "#2196F3",
            type: "error"
        });
      }
    });
  }


</script>
@endsection 
