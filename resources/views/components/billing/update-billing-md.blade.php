<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">Update Billing</h5>
</div>
<form class="form-horizontal" id="form-billing">
  {{ csrf_field() }}
  <div class="modal-body">
		<fieldset class="content-group">
      <input type="hidden" name="account_role_id" value="{{$account_role->id}}">
      <label class="control-label col-lg-3">Billing rate</label>
      <div class="col-lg-9">
      <input type="text" class="form-control" name="billing_rate" value="@if($account_role->billing_rate!=''){{$account_role->billing_rate}}@else 0.00 @endif">
      </div>
		</fieldset>
  </div>

  <div class="modal-footer">
  	<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
  	<button type="submit" class="btn btn-primary">Save Changes</button>
  </div>
</form>
<script type="text/javascript">
  $( "#form-billing" ).submit(function( e ) {
    $.ajax({ 
      type: "POST",
      url: "{{ url('admin-console/update-billing') }}",
      data: $('#form-billing').serialize(),
      beforeSend: function(){
        $('body').addClass('wait-pointer');
      },
      complete: function(){
        $('body').removeClass('wait-pointer');
      },
      success: function (data) {
        var res = $.parseJSON(data);
        if( res.status == 1 ){
          swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success"
          });  
          $("#modal-update").modal('toggle');
          fetch_table();
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
