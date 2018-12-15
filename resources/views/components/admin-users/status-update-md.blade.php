<style type="text/css">
	.form-horizontal .checkbox .checker{
		top: 18px;
  }
</style>
<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Update User Status</h4>
</div>
<form class="form-horizontal" id="form-status-update">
	{{ csrf_field() }}
	<input type="hidden" name="_id" value="{{ Crypt::encrypt($data[0]->id) }}">
	<div class="modal-body">
		<h5>{{ $data[0]->fname." ".$data[0]->lname }}</h5><hr>
		<div class="form-group">
			<label class="control-label col-lg-3">Status :</label>
			<div class="col-lg-9">
				<select class="form-control" name="status" required>
					<option value="active" @if($data[0]->status == 'active') selected @endif>Active</option>
					<option value="deactivated" @if($data[0]->status == 'deactivated') selected @endif>Deactivate</option>
					<option value="pending" @if($data[0]->status == 'pending') selected @endif>Pending</option>
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</form>

<script type="text/javascript">
  $( "form" ).submit(function( e ) {
		$.ajax({ 
      	type: "POST",
      	url: "{{ url('admin/update-status') }}",
      	data: $(this).serialize(),
      	success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
       		dt_admin.search('').draw(); 
          dt_client.search('').draw(); 
       		swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success" 
        	});  
        	$("#modal-update-xs").modal('toggle');
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
