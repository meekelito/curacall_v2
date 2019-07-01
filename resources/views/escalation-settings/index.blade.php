@extends('layouts.app')

@section('content')
	<!-- Page header -->
	<div class="page-header page-header-default">
	    <div class="page-header-content">
	        <div class="page-title">
	            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Escalation Settings</h4>
	        </div>
	    </div>
	    <div class="breadcrumb-line">
	        <ul class="breadcrumb">
	            <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
	            <li class="active">Escalation Settings</li>
	        </ul>
	    </div>
	</div>
	<!-- /page header -->
	<div class="content">
		<div class="panel panel-flat">
		      <div class="panel-body">
	              <table class="table tbl-calltype" cellspacing="0" width="100%">
	                <thead>
	                  <tr> 
	                    <th>Calltype</th><th>Notification Interval</th><th>Conditions</th>
	                  </tr>
	                </thead>
	                <tbody>
	                </tbody>
	                 <tfoot>
	                  <tr>
	                    <th>Calltype</th><th>Notification Interval</th><th>Conditions</th>
	                  </tr>
	                </tfoot>
	              </table>
		      </div>
		</div>
	</div>

<div id="modal-cron" class="modal" data-backdrop="static">
    <div class="modal-dialog modal-md">
      <div class="modal-content content-edit-role">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">CRON Settings</h5>
          </div>
          <form class="form-horizontal" id="frmCron" method="POST" action="{{ route('escalation-settings.updatecron') }}">
          @csrf
          <input type="hidden" name="_method" value="PUT">
    	  <input type="hidden" id="calltype_id" name="id">
				<div class="modal-body">
					<h6 class="text-semibold">Backup 1</h6>
					<hr>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Trigger after
						</label>
						<div class="col-lg-8">
							<select class="form-control" id="attempt1" name="data[backup_1][attempt]" required>
								<option value="1">1st Attempt</option>
								<option value="2">2nd Attempt</option>
								<option value="3">3rd Attempt</option>
								<option value="4">4th Attempt</option>
								<option value="5">5th Attempt</option>
								<option value="6">6th Attempt</option>
								<option value="7">7th Attempt</option>
								<option value="8">8th Attempt</option>
								<option value="9">9th Attempt</option>
								<option value="10">10th Attempt</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Notify URL :
						</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="data[backup_1][notify_url]" value="" required>
						</div>
					</div>
					
					<h6 class="text-semibold">Backup 2</h6>
					<hr>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Trigger after
						</label>
						<div class="col-lg-8">
							<select class="form-control" id="attempt2" name="data[backup_2][attempt]" required>
								<option value="1">1st Attempt</option>
								<option value="2">2nd Attempt</option>
								<option value="3">3rd Attempt</option>
								<option value="4">4th Attempt</option>
								<option value="5">5th Attempt</option>
								<option value="6">6th Attempt</option>
								<option value="7">7th Attempt</option>
								<option value="8">8th Attempt</option>
								<option value="9">9th Attempt</option>
								<option value="10">10th Attempt</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Notify URL :
						</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="data[backup_2][notify_url]" value="" required>
						</div>
					</div>

					<h6 class="text-semibold">Backup 3</h6>
					<hr>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Trigger after
						</label>
						<div class="col-lg-8">
							<select class="form-control" id="attempt3" name="data[backup_3][attempt]" required>
								<option value="1">1st Attempt</option>
								<option value="2">2nd Attempt</option>
								<option value="3">3rd Attempt</option>
								<option value="4">4th Attempt</option>
								<option value="5">5th Attempt</option>
								<option value="6">6th Attempt</option>
								<option value="7">7th Attempt</option>
								<option value="8">8th Attempt</option>
								<option value="9">9th Attempt</option>
								<option value="10">10th Attempt</option>
							</select>
						</div>
					</div>
					<div class="form-group form-group-xs">
						<label class="control-label col-lg-4 text-right">
							<span class="text-danger">*</span> Notify URL :
						</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="data[backup_3][notify_url]" value="" required>
						</div>
					</div>
				</div>

		
        

          <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
          </form>
      </div>
    </div>
  </div>

@endsection  

@section('script')

<script type="text/javascript">
  var dt_calltype;
  $(document).ready(function () {

    $(".menu-curacall li").removeClass("active");
    $(".menu-escalation-settings").addClass('active');

    dt_calltype = $('.tbl-calltype').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ route('escalation-settings.calltypes') }}",
      columns: [
        {data: 'call_type.name'},
	    { 
	        "orderable": false,
	        "searchable": false,
	        "render": function (data, type, full, meta){
	            return "<div class='btn-group'>"+
							"<a href='#' class='badge bg-indigo-400 dropdown-toggle' data-toggle='dropdown'>"+full.interval_minutes +" minutes <span class='caret'></span></a>"+

							"<ul class='dropdown-menu dropdown-menu-right'>"+
								"<li><a href='javascript:update_minutes("+ full.id +",5)'>5 minutes</a></li>"+
								"<li><a href='javascript:update_minutes("+ full.id +",10)'>10 minutes</a></li>"+
								"<li><a href='javascript:update_minutes("+ full.id  +",15)'>15 minutes</a></li>"+
								"<li><a href='javascript:update_minutes("+ full.id  +",20)'>20 minutes</a></li>"+
								"<li><a href='javascript:update_minutes("+ full.id  +",30)'>30 minutes</a></li>"+
							"</ul>"+
						"<//div>";
	        }
	    },
        { 
        	"data": 'cron_settings',
	        "orderable": false,
	        "searchable": false,
	        "render": function (data, type, full, meta){
	        	if(data !== null)	
	            	return "<span class='label label-danger' style='cursor:pointer' onclick='show_cron_modal("+full.id+")'>Edit <i class='icon-pencil5'></i></span>";
	            else
	            	return "<span class='label label-flat border-success text-success-600 label-danger' style='cursor:pointer' onclick='show_cron_modal("+full.id+")'>Setup</span>";
	        }
	    },
      ]
    });

	});

  
	
	

  function update_minutes(id,minutes)
  {
	    $.ajax({ 
	      type: "POST",
	      url: "{{ route('escalation-settings.updateinterval') }}",
	      data: { 
	        _token : '{{ csrf_token() }}',
	        _method: 'PUT',
	        id: id,
	        interval: minutes 
	      }, 
	      success: function (data) {  
	           dt_calltype.search('').draw();
	      },
	      error: function (data){
	        swal({
	          title: "Oops..!",
	          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
	          confirmButtonColor: "#EF5350",
	          type: "error"
	        });
	      }
	    });
  }

  function show_cron_modal(id)
  {
  	$('#frmCron').trigger("reset");

  	 $.ajax({
            url: "{{ route('escalation-settings.show') }}", 
            type: "GET",             
            data: { id : id },      
            beforeSend: function(){
               $('body').addClass('wait-pointer');
            },
            complete: function(){
              $('body').removeClass('wait-pointer');
            },          
            success: function(data) {
              var result = $.parseJSON(data.cron_settings);

            if(result != undefined)
          	{
          		$('#attempt1').val(result.backup_1.attempt);
          		$('input[name="data[backup_1][notify_url]"]').val(result.backup_1.notify_url);

          		$('#attempt2').val(result.backup_2.attempt);
          		$('input[name="data[backup_2][notify_url]"]').val(result.backup_2.notify_url);

          		$('#attempt3').val(result.backup_3.attempt);
          		$('input[name="data[backup_3][notify_url]"]').val(result.backup_3.notify_url);
          	}

            

          	  $('#calltype_id').val(id);
              $('#modal-cron').modal('show');
            },
            error: function(data, errorThrown)
            {
                //$(block).unblock()
                // $('#content').unblock();
                // notify('request failed :'+errorThrown,"error");
                //    notify(data.responseJSON.error[Object.keys(data.responseJSON.error)[0]]);
            }
        });
  }

  	$('#frmCron').on('submit',function (ev) {
      ev.preventDefault();
        swal({
            title: "Are you sure you want to update?",
            text: "Previous queue won't be affected",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Update",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {

                var frm = $('#frmCron');
                  $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action'),
                    data: frm.serialize(),
                    beforeSend: function(){
                      $('body').addClass('wait-pointer');
                    },
                    complete: function(){
                      $('body').removeClass('wait-pointer');
                    },
                    success: function (data) {
                      if( data.status == 1 ){
                      
                        swal({
                          title: "Good job!",
                          text: data.message,
                          confirmButtonColor: "#66BB6A",
                          type: "success"
                        }, function() {
                            $('#modal-cron').modal('hide');
                            dt_calltype.search('').draw();
                        });  
                      
                      }else{
                        swal({
                          title: "Oops..!",
                          text: data.message,
                          confirmButtonColor: "#EF5350",
                          type: "error"
                        }); 
                      }
                    },
                    error: function (data) {
                      swal({
                        title: "Oops! Something went wrong",
                        text: data.responseJSON.errors.name[0],
                        confirmButtonColor: "#EF5350",
                        type: "error"
                      });
                    },
                });
                return false;
            }
        });
    });
</script>
@endsection 
