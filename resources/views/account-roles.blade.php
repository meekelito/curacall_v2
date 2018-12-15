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
  $(".bootstrap-select").selectpicker();
	var dt;
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-account-roles").addClass('active');

    dt = $('.tbl-client-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('account/table-roles') }}",
      columns: [
        {data: 'role_title'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });
	}); 

	
	function client_role_md(id) { 
    $.ajax({ 
      type: "POST", 
      url: "{{ url('update-role-md') }}",
      data: { 
        _token : '{{ csrf_token() }}',
        id : id
      },
      success: function (data) {  
        $(".content-data-update-md").html( data );
        $("#modal-update-md").modal('show');
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
</script>
@endsection 
