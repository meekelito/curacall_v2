<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">Update Account</h5>
</div>
<form class="form-horizontal" id="form-account-update">
	{{ csrf_field() }}
	<input type="hidden" name="_id" value="{{ Crypt::encrypt($data[0]->id) }}">
	<div class="modal-body">
		<h6 class="text-semibold">Account information</h6>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Account Name :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="account_name" value="{{ $data[0]->account_name }}" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Address Primary:</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="address_main" value="{{ $data[0]->address_main }}">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Address Secondary :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="address_secondary" value="{{ $data[0]->address_secondary }}">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">City :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="city" value="{{ $data[0]->city }}">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">State :</label>
			<div class="col-lg-8">
				<select class="form-control" name="state">
					<option></option>
					@foreach($state as $row)
					<option value="{{ $row->id }}" @if($row->id == $data[0]->state ) selected @endif>{{ $row->state }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">Zipcode :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="zipcode" value="{{ $data[0]->zipcode }}">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Main Number :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" data-mask="(999) 999-9999" name="phone_main" value="{{ $data[0]->phone_main }}" required>
			</div>
		</div>
		<h6 class="text-semibold">Support contact information</h6>
		<div class="form-group">
			<label class="control-label col-lg-4 text-right"> 
				<span class="text-danger">*</span> Info:
			</label>
			<div class="col-lg-8">
				<textarea class="form-control" name="account_info" required>{{ $data[0]->account_info }}</textarea>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Email :
			</label>
			<div class="col-lg-8">
				<input type="email" class="form-control" name="email" value="{{ $data[0]->email }}" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Phone :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" data-mask="(999) 999-9999" name="phone_secondary" value="{{ $data[0]->phone_secondary }}" required>
			</div>
		</div> 

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</form>

<script type="text/javascript">
	// $(".bootstrap-select").selectpicker(); 
	$( "#form-account-update" ).submit(function( e ) {
		$.ajax({ 
	      	type: "POST", 
	      	url: "{{ url('admin/update-account') }}",
	      	data: $(this).serialize(),
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
