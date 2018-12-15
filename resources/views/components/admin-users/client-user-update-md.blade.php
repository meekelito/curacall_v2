<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">Update Client User Information</h5>
</div>
<form class="form-horizontal" id="form-client-user-update">
	{{ csrf_field() }}
	<input type="hidden" name="id" value="{{ Crypt::encrypt($data[0]->id) }}">
	<div class="modal-body">
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Curacall ID :</label>
			<label class="control-label col-lg-8 "><strong>{{ 'CC'.str_pad($data[0]->id, 6,'0',STR_PAD_LEFT)  }}</strong></label>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">First Name :</label>
			<div class="col-lg-8"> 
				<input type="text" class="form-control" value="{{ $data[0]->fname }}" name="fname" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Last Name :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" value="{{ $data[0]->lname }}" name="lname" required>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Prof. Suffix :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" value="{{ $data[0]->prof_suffix }}" name="prof_suffix"  placeholder="ex. MD, PA, RN">
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Title :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" value="{{ $data[0]->title }}" name="title">
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Email :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" value="{{ $data[0]->email }}" name="email" required>
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Mobile no :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" value="{{ $data[0]->mobile_no }}" name="mobile_no">
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right" >Phone no :</label>
			<div class="col-lg-8">
				<input type="text" class="form-control" data-mask="(999) 999-9999" value="{{ $data[0]->phone_no }}" name="phone_no">
			</div>
		</div>

		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Role :</label>
			<div class="col-lg-8">
				<select class="form-control" name="role_id" required>
					@foreach($roles as $row)
					<option value="{{ Crypt::encrypt($row->id) }}" @if($data[0]->role_id == $row->id) selected @endif>{{ $row->role_title }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group form-group-xs">
			<label class="control-label col-lg-3 text-right">Account :</label>
			<div class="col-lg-8">
				<select class="form-control" name="account_id" required>
					@foreach($accounts as $row)
					<option value="{{ Crypt::encrypt($row->id) }}" @if($data[0]->account_id == $row->id) selected @endif>{{ $row->account_name }}</option>
					@endforeach 
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
	// $(".bootstrap-select").selectpicker();
	$( "#form-client-user-update" ).submit(function( e ) {
		$.ajax({  
      type: "POST",
      url: "{{ url('admin/update-client-user') }}",
      data: $('#form-client-user-update').serialize(),
      success: function (data) {
        var res = $.parseJSON(data);
       	if( res.status == 1 ){
       		dt_client.search('').draw(); 
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