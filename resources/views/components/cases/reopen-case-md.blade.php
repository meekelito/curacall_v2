<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Re-Open Case</h4>
</div>
<form class="form-horizontal" id="form-reopen-case">
	{{ csrf_field() }}
	<input type="hidden" name="case_id" value="{{ $case_id }}" required>
	<div class="modal-body">
    <fieldset class="content-group">
      <div class="form-group">
        <label class="control-label col-lg-3 text-right">Remarks/Note :</label>
        <div class="col-lg-9">
          <textarea class="form-control" name="note" rows="3" required autofocus></textarea>
        </div>
      </div>
    </fieldset>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	</div>
</form>

<script type="text/javascript">
  $( "#form-reopen-case" ).submit(function( e ) {
    $.ajax({  
      type: "POST",
      url: "{{ url('reopen-case') }}",
      data: $('#form-reopen-case').serialize(),
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
          count_case();
          fetchCase();
          dt.search('').draw();
          $("#modal-case").modal('hide');
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



