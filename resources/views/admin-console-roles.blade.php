@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Roles</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">Roles</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-body">
        <div class="tabbable">
          <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="active"><a href="#highlighted-justified-tab1" data-toggle="tab">CuraCall Roles</a></li>
            <li><a href="#highlighted-justified-tab2" data-toggle="tab">Client Roles</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="highlighted-justified-tab1">        
              <table class="table tbl-admin-roles" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                 <tfoot>
                  <tr>
                    <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="tab-pane" id="highlighted-justified-tab2">
              <form class="form-horizontal">
                <div class="col-lg-12">
                  <div class="form-group form-group-lg">
                    <div class="col-lg-4">
                      <select class="form-control input-lg"  id="_account">
                        <option value=""></option>
                        @foreach($accounts as $row)
                        <option value="{{ Crypt::encrypt($row->id) }}">{{ $row->account_name }}</option>
                        @endforeach
                      </select> 
                    </div>
                  </div>
                  <hr>
                  <table class="table tbl-client-roles" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                     <tfoot>
                      <tr>
                        <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </form>
            </div>
          </div>
        </div>
			</div>
		</div>
	</div>
	<div id="modal_default modal-add-md" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data-add-md">

      </div>
    </div>
  </div>

  <div id="modal-update-md" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data-update-md">

      </div>
    </div>
  </div>
@endsection  

@section('script')
<script type="text/javascript">

	var dt_admin,dt_client;
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-admin-console-roles").addClass('active');

	  dt_admin = $('.tbl-admin-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
	    "language": {
	      "search": " Search : "
	    },
      ajax: "{{ url('admin/admin-roles') }}",
      columns: [
        {data: 'role_title'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 

    dt_client = $('.tbl-client-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('admin/client-roles') }}",
      columns: [
        {data: 'role_title'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });
	}); 

  function admin_role_md(id) { 
    swal({
      title: "For your information",
      text: "This function is not yet available.",
      confirmButtonColor: "#2196F3",
      type: "info"
    });
  }
	
	function client_role_md(id) { 
    var account = document.getElementById('_account').value;
    if( account == "" ){
      swal({
        title: "Oops..!",
        text: "Please select an account.",
        confirmButtonColor: "#FF5722",
        type: "warning"
      });
    }else{
      $.ajax({ 
        type: "POST", 
        url: "{{ url('update-client-role-md') }}",
        data: { 
          _token : '{{ csrf_token() }}',
          id : id,
          account : account
        },
        success: function (data) {  
          $(".content-data-update-md").html( data );
          $('#modal-update-md').modal('show');
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
  }
</script>
@endsection 
