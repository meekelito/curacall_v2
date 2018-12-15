<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h5 class="modal-title">{{ $account[0]->account_name }}</h5>
</div>
<form class="form-horizontal" id="form-role">
  {{ csrf_field() }}
  <input type="hidden" name="id" value="{{ Crypt::encrypt($data[0]->id) }}">
  <div class="modal-body">
		<fieldset class="content-group">
			<div class="form-group">
				<label class="control-label col-lg-3 text-right">Role Title :</label>
				<div class="col-lg-9">
					<input type="text" class="form-control" name="role_title" value="{{ $data[0]->role_title }}">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 text-right">Description :</label>
				<div class="col-lg-9">
					<input type="text" class="form-control" name="description" value="{{ $data[0]->description }}">
				</div>
			</div>	
			<div class="form-group">
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_acaregiver" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_acaregiver ){ checked }@endif>
           Can <ins>never</ins> send a message <ins>unless</ins> a message was received.
          </label>
        </div>
      </div>
      <div class="form-group con-time @if(!$data[0]->msg_acaregiver){ hidden }@endif">
        <label class="control-label col-lg-3 text-right">Time :</label>
        <div class="col-lg-9">
          <select class="form-control" name="msg_time">
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == 'Always' ){ selected }@endif>Always</option>
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == '1 hour' ){ selected }@endif>1 hour</option>
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == '4 hours' ){ selected }@endif>4 hours</option>
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == '8 hours' ){ selected }@endif>8 hours</option>
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == '12 hours' ){ selected }@endif>12 hours</option>
            <option @if( $data[0]->msg_acaregiver && $data[0]->msg_time == '24 hours' ){ selected }@endif>24 hours</option>
          </select>
        </div>
      </div>  
      <div class="form-group">
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_caregiver" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_caregiver ){ checked }@endif>
            Can send a message to caregiver.
          </label>
        </div>
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_nursing" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_nursing ){ checked }@endif>
            Can send a message to nursing.
          </label>
        </div>
  			<div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_coordinator" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_coordinator ){ checked }@endif>
            Can send a message to coordinator.
          </label>
        </div>
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_management" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_management ){ checked }@endif>
            Can send a message to management.
          </label>
        </div>
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_account_admin" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_account_admin ){ checked }@endif>
            Can send a message to account admin.
          </label>
        </div>
        <div class="col-lg-10 col-lg-push-2 checkbox checkbox-switch">
          <label>
            <input type="checkbox" class="switch" name="msg_all" value="1" data-on-text="On" data-off-text="Off" @if( $data[0]->msg_all ){ checked }@endif>
            Can send a message to <span class="text-bold">anyone</span>.
          </label>
        </div>
      </div>
		</fieldset>
  </div>

  <div class="modal-footer">
  	<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
  	<button type="submit" class="btn btn-primary">Save Changes</button>
  </div>
</form>
<script type="text/javascript">
  $(".switch").bootstrapSwitch();
  $(document).ready(function () {
    $(".switch").on('switchChange.bootstrapSwitch', function (event, state) {
      $(".con-time").addClass('hidden');
      if($("input[name='msg_all']").is(":checked") && this.name == 'msg_all'){ 
        $("input[name='msg_account_admin']").bootstrapSwitch("state", false);
        $("input[name='msg_management']").bootstrapSwitch("state", false);
        $("input[name='msg_nursing']").bootstrapSwitch("state", false);
        $("input[name='msg_caregiver']").bootstrapSwitch("state", false);
        $("input[name='msg_coordinator']").bootstrapSwitch("state", false);
        $("input[name='msg_acaregiver']").bootstrapSwitch("state", false);
        $("input[name='msg_all']").bootstrapSwitch("state", true);
      }else if($("input[name='msg_acaregiver']").is(":checked") && this.name == 'msg_acaregiver'){
        $("input[name='msg_account_admin']").bootstrapSwitch("state", false);
        $("input[name='msg_management']").bootstrapSwitch("state", false);
        $("input[name='msg_nursing']").bootstrapSwitch("state", false);
        $("input[name='msg_caregiver']").bootstrapSwitch("state", false);
        $("input[name='msg_coordinator']").bootstrapSwitch("state", false);
        $("input[name='msg_all']").bootstrapSwitch("state", false);
        $("input[name='msg_acaregiver']").bootstrapSwitch("state", true);
        $(".con-time").removeClass("hidden");
      }else if($("input[name='msg_caregiver']").is(":checked") 
        && $("input[name='msg_nursing']").is(":checked") 
        && $("input[name='msg_management']").is(":checked") 
        && $("input[name='msg_coordinator']").is(":checked") 
        && $("input[name='msg_account_admin']").is(":checked")){
        $("input[name='msg_all']").bootstrapSwitch("state", true);
      }else{
        $("input[name='msg_all']").bootstrapSwitch("state", false);
        $("input[name='msg_acaregiver']").bootstrapSwitch("state", false);
      }
    });

    $( "#form-role" ).submit(function( e ) {
      $.ajax({  
        type: "POST",
        url: "{{ url('admin/update-client-role') }}",
        data: $('#form-role').serialize(),
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
  });
</script>