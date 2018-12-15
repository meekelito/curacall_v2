@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - General Information</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">General Information</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-body"> 
				<form class="form-horizontal" id="form-general-info">
					{{ csrf_field() }}
					<fieldset class="content-group">
						<h6 class="text-semibold">Company Information</h6>
						<div class="form-group">
							<label class="control-label col-lg-4 text-right"><span class="text-danger">*</span> Company Name:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="account_name" value="{{ $data[0]->account_name }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">Main location address 1:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="address_main" value="{{ $data[0]->address_main }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">Main location address 2:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="address_secondary" value="{{ $data[0]->address_secondary }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">City:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="city" value="{{ $data[0]->city }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">State:</label>
							<div class="col-lg-8">
								<select class="form-control" name="state">
									@foreach($state as $row)
									<option value="{{ $row->code }}" @if($data[0]->state == $row->code) selected @endif>{{ $row->state }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">Zip:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="zipcode" value="{{ $data[0]->zipcode }}">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right">Main Number:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="phone_main" data-mask="(999) 999-9999" value="{{ $data[0]->phone_main }}">
							</div>
						</div>
						<hr>
						
						<h6 class="text-semibold">Support Contact Information</h6>
						<div class="form-group">
							<label class="control-label col-lg-4 text-right">Info:</label>
							<div class="col-lg-8">
								<textarea class="form-control" name="company_info">{{ $data[0]->company_info }}</textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right"><span class="text-danger">*</span> Email:</label>
							<div class="col-lg-8">
								<input type="email" class="form-control" name="email" value="{{ $data[0]->email }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-lg-4 text-right"><span class="text-danger">*</span> Phone:</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="phone_secondary" data-mask="(999) 999-9999" value="{{ $data[0]->phone_secondary }}" required>
							</div>
						</div>

						<div class="text-right">
							<button type="reset" class="btn btn-link">Cancel</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>

					</fieldset>
				</form>
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

@endsection  

@section('script')

<script type="text/javascript">

  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
	  $(".menu-account-general-info").addClass('active');
	});

  $( "#form-general-info" ).submit(function( e ) {
		$.ajax({ 
      type: "POST", 
      url: "{{ url('account/general-info') }}",
      data: $('#form-general-info').serialize(),
      success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
          swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success"
        	}); 
       	}else{
          swal({
            title: "Oops...",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "error"
        	});
       	}
      },
      error: function (data) {
        swal({
          title: "Oops...",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.!",
          confirmButtonColor: "#EF5350",
          type: "error"
      	});
      },
    }); 
		e.preventDefault();
	});
</script>
@endsection 
