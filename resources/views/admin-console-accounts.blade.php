@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Accounts</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">Admin Accounts</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-body">
				<button type="button" class="btn btn-primary btn-account-add">Add New Account</button>
				<br><br>
				<table class="table tbl-accounts" cellspacing="0" width="100%">
          <thead>
            <tr> 
             	<th>ID</th><th>Account Name</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
           <tfoot>
            <tr>
             	<th>ID</th><th>Account Name</th><th>Actions</th>
            </tr>
          </tfoot>
        </table>
			</div>
		</div>
	</div>

	<div id="modal-add" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content content-data-add">

			</div>
		</div>
	</div>

	<div id="modal-update" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content content-data-update">

			</div>
		</div>
	</div>

@endsection  

@section('script')

<script type="text/javascript">
	var dt_accounts;
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-admin-console-accounts").addClass('active');

	  dt_accounts = $('.tbl-accounts').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('admin/admin-accounts') }}",
      columns: [
        {data: 'id'},
        {data: 'account_name'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });
	});
	
	$(".btn-account-add").click(function(){
		$.ajax({ 
      type: "POST", 
      url: "{{ url('add-account-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}'
      },
      success: function (data) {  
        $(".content-data-add").html( data );
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


	function update_account_md(id){
		$.ajax({ 
      type: "POST",
      url: "{{ url('update-account-md') }}",
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

</script>
@endsection 
