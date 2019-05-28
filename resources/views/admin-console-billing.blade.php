@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Billing</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">Billing</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-body"> 
				<div class="row">
					<div class="col-sm-5">
						<select class="select select-search account-list" style="width: 100% !important;" data-placeholder="Select Account"> 
							<option></option>
			        @foreach($accounts as $account)
			        <option value="{{ $account->id }}">{{ $account->account_name }}</option>
			        @endforeach
			      </select> 
		      </div>
	    	</div>
	      <div class="row">
	      	<div class="col-sm-12 content-data-billing">
	      		<table class="table" style="margin-top: 40px;">
						<tr><td colspan="3">No data found.</td></tr>
					</table>

	      	</div>
	      </div>
			</div>
		</div>
	</div>

	<div id="modal-add" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-sm">
			<div class="modal-content content-data">

			</div>
		</div>
	</div>

	<div id="modal-update" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-sm">
			<div class="modal-content content-data-update">

			</div>
		</div>
	</div>

@endsection  

@section('script')

<script type="text/javascript">
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-admin-console-billing").addClass('active');
	  $('.select-search').select2();

	  $(".account-list").change(function(event) {
			fetch_table();
		});
	});
	function fetch_table(account_id){
		$.ajax({ 
	    type: "POST", 
	    url: "{{ url('account-billing') }}", 
	    data: { 
	      _token : '{{ csrf_token() }}',
	      account_id: $('.account-list').val()
	    },
	    beforeSend: function(){
	        $('body').addClass('wait-pointer');
	      },
	    complete: function(){
	        $('body').removeClass('wait-pointer');
	      },
	    success: function (data) {  
	      $(".content-data-billing").html( data );
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
	function update_billing(id){
		$.ajax({ 
      type: "POST", 
      url: "{{ url('admin-console/update-billing-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        account_role: id
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
      complete: function(){
          $('body').removeClass('wait-pointer');
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
