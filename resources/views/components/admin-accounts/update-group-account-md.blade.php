<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">Update Group</h5>
</div>
<form class="form-horizontal" id="form-group-account-update">
	{{ csrf_field() }}
	<input type="hidden" name="_id" value="{{ $data[0]->id }}">
	<div class="modal-body">
		<h6 class="text-semibold">Group information</h6>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Group Name :
			</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" name="group_name" value="{{ $data[0]->group_name }}" required>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-4 text-right">
				<span class="text-danger">*</span> Info:
			</label>
			<div class="col-lg-8">
				<textarea class="form-control" name="group_info" required>{{ $data[0]->group_info }}</textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</form>

<script type="text/javascript">
	$( "#form-group-account-update" ).submit(function( e ) {
		$.ajax({ 
    	type: "POST", 
    	url: "{{ url('admin/update-group-account') }}",
    	data: $("#form-group-account-update").serialize(),
    	beforeSend: function(){
        $('body').addClass('wait-pointer');
      },
      complete: function(){
        $('body').removeClass('wait-pointer');
      },
    	success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
       		dt_account_group.search('').draw();
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
