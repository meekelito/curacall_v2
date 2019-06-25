@extends('layouts.app')
@section('css')
<style type="text/css">
  .content-data-reports-all-messages tr td {
    white-space: nowrap;
  }
</style>
@endsection 

@section('content')
  <!-- Page header -->
  <div class="page-header page-header-default">
      <div class="page-header-content">
          <div class="page-title">
              <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reports</span> - Excalated Tickets</h4>
          </div>
      </div>
      <div class="breadcrumb-line">
          <ul class="breadcrumb">
              <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
              <li class="active">Excalated Tickets</li>
          </ul>
      </div>
  </div>
  <!-- /page header -->
  <div class="content">
    <div class="panel panel-flat">
      <div class="panel-body"> 
        <div class="row">
          <div class="col-sm-5 multi-select-full">
            <select class="multiselect-select-all" multiple="multiple" style="width: 100% !important" data-placeholder="Select Account" required> 
              @foreach($accounts as $account)
              <option value="{{ $account->id }}">{{ $account->account_name }}</option>
              @endforeach
            </select> 
          </div>
          <div class="col-sm-2">
            <input type="date" class="form-control" name="date_from" id="date_from" required>
          </div>
          <div class="col-sm-2">
            <input type="date" class="form-control" name="date_to" id="date_to" required>
          </div>
          <button type="button" class="btn btn-primary btn-icon btn-search"><i class="icon-search4"></i></button>
        </div>
        <div class="row">
          <div class="col-sm-12 content-data-reports-all-messages pre-scrollable">
            <table class="table scrollable " style="margin-top: 40px; ">
              <tr class="bg-primary">
                <td>CRM Link</td>
                <td>Call Received On</td>
                <td>Date Time Delivered</td>
                <td>Message Read By</td>
                <td>Language</td>
                <td>Time of Call</td>
                <td>Full Message</td>
                <td>Call Type</td>
                <td>Call Subtype</td>
                <td>Reason Call</td>
                <td>Reason of the Cancellation</td>
                <td>Reason for Being Late</td>
                <td>How Late Will You Be To Your Shift</td>
                <td>Doesn't know How Late Will Be To Shift</td>
                <td>Name of the Hospital</td>
                <td>Emergency</td>
                <td>Typology</td>
                <td>Actions Taken</td>
                <td>Caller Type</td>
                <td>Caregiver</td>
                <td>Relation to PT</td>
                <td>Relation to Field Worker</td>
                <td>Caller First Name</td>
                <td>Caller Last Name</td>
                <td>Caller Email Address</td>
                <td>Caller Telephone</td>
                <td>Caller Position Interested In</td>
                <td>Company Name</td>
                <td>Patient First Name</td>
                <td>Patient Last Name</td>
                <td>Patient Telephone</td>
                <td>Referral</td>
                <td>First Visit</td>
                <td>FieldWorker First Name</td>
                <td>FieldWorker Last Name</td>
                <td>Shift Start</td>
                <td>Shift End</td>
                <td>Services Requested</td>
              </tr>
              <tr><td colspan="9">No data found.</td></tr>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-add" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data">

      </div>
    </div>
  </div>

  <div id="modal-update" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data-update">

      </div>
    </div>
  </div>

@endsection  

@section('script')

<script type="text/javascript">
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-admin-console-reports").addClass('active');
    $(".menu-reports-escalated-tickets").addClass('active'); 
    
    
    $('.multiselect-select-all').multiselect({
        includeSelectAllOption: true,
        onSelectAll: function() {
            $.uniform.update();
        }
    });
    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

    $(".btn-search").click(function(){
      var is_check = 0;
      if($(".styled").is(":checked")){
         is_check = 1;
      }
      if( $('.multiselect-select-all').val() != null && $('#billing_month').val() != "" ){
        $.ajax({ 
          type: "POST", 
          url: "{{ url('admin-console/reports-billing') }}", 
          data: { 
            _token : '{{ csrf_token() }}',
            account_id: $('.multiselect-select-all').val(),
            billing_month: $('#billing_month').val(),
            is_check : is_check
          },
          beforeSend: function(){
              $('body').addClass('wait-pointer');
            },
          complete: function(){
              $('body').removeClass('wait-pointer');
            },
          success: function (data) {  
            $(".content-data-reports-billing").html( data );
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
      }else{
        alert("Account and Date field is required.");
      }
    });
   
  });
</script>
@endsection 
