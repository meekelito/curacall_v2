<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">New Account</h5>
</div>
<form class="form-horizontal" id="form-account-info">
	{{ csrf_field() }}
	<div class="modal-body">
		<h6 class="text-semibold">Account information</h6>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Account Name :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="account_name" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Address Primary:</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="address_main">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Address Secondary :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="address_secondary">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">City :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="city">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">State :</label>
			<div class="col-lg-8">
				<select class="form-control" name="state">
					<option></option>
					@foreach($state as $row)
					<option value="{{ $row->id }}">{{ $row->state }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Zipcode :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="zipcode">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Main Number :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" data-mask="(999) 999-9999"  name="phone_main" required>
			</div>
		</div>
		<h6 class="text-semibold">Support contact information</h6>
		<div class="form-group">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Info:
			</label>
			<div class="col-lg-8">
				<textarea class="form-control" name="account_info" required></textarea>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Email :
			</label>
			<div class="col-lg-8">
				<input type="email" class="form-control" name="email" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Phone :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" data-mask="(999) 999-9999"  name="phone_secondary" required>
			</div>
		</div> 

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	</div>
</form>

<script type="text/javascript">
	$( "#form-account-info" ).submit(function( e ) {
		$.ajax({ 
      type: "POST",
      url: "{{ url('admin/add-account') }}",
      data: $('#form-account-info').serialize(),
      success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
       		dt_accounts.search('').draw();
       		swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success"
        	});  
        	$("#modal-add").modal('toggle');
       	}else if( res.status == 2 ){
	        var error_message="";
	        $.each(res.message,function(index,item){
	            error_message+=item+",";
	        }); 
	        var error_message = error_message.replace(/,/g, "\n")
	        swal({
	            title: "Oops...",
	            text: error_message,
	            confirmButtonColor: "#EF5350",
	            type: "error"
	        });
        }else{
       		swal({
            title: "Oops..!",
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
