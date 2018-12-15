<style type="text/css">
	.form-horizontal .checkbox .checker{
		top: 18px;
  }
</style>
<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">New CuraCall User</h5>
</div>
<form class="form-horizontal" id="form-admin-user-new">
	{{ csrf_field() }}
	<div class="modal-body">
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">
				<span class="text-danger">*</span> First Name :
			</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="fname" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">
				<span class="text-danger">*</span> Last Name :
			</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="lname" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Prof. Suffix :</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="prof_suffix" placeholder="ex. MD, PA, RN">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Title :</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="title">
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">
				<span class="text-danger">*</span> Email :
			</label>
			<div class="col-lg-9">
				<input type="email" class="form-control" name="email" required>
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Mobile no :</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="mobile_no">
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Phone no :</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" data-mask="(999) 999-9999" name="phone_no">
			</div>
		</div> 

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">
				<span class="text-danger">*</span> Role :
			</label>
			<div class="col-lg-9">
				<select class="form-control" name="role_id" required>
					<option></option>
					@forelse($roles as $row)
					<option value="{{ Crypt::encrypt($row->id) }}">{{ $row->role_title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	</div>
</form>

<script type="text/javascript">

	$( "#form-admin-user-new" ).submit(function( e ) {
		$.ajax({ 
      	type: "POST",
      	url: "{{ url('admin/add-admin-user') }}",
      	data: $('#form-admin-user-new').serialize(),
      	success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
       		dt_admin.search('').draw(); 
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
