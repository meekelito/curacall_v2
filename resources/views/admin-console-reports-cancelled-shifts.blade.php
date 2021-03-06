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
              <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reports</span> - Cancelled Shifts</h4>
          </div>
      </div>
      <div class="breadcrumb-line">
          <ul class="breadcrumb">
              <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
              <li class="active">Cancelled Shifts</li>
          </ul>
      </div>
  </div>
  <!-- /page header -->
  <div class="content">
    <div class="panel panel-flat">
      <div class="panel-body"> 
        <div class="row">
          <div class="col-sm-5 multi-select-full">
            <select class="form-control account_list" required> 
              <option>Select Account</option>
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
          <div class="col-sm-12 content-data-reports-cancelled-shifts">
            <table class="table" style="margin-top: 40px;">
              <tr class="bg-primary">
                <td>Staff Name</td>
                <td>Number</td>
              </tr>
              <tr><td colspan="2">No data found.</td></tr>
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
    $(".menu-reports-cancelled-shifts").addClass('active'); 
    
    $(".btn-search").click(function(){
      $.ajax({ 
        type: "POST", 
        url: "{{ url('admin-console/reports-cancelled-shifts-table') }}", 
        data: { 
          _token : '{{ csrf_token() }}',
          account_id: $('.account_list').val(),
          month_from: $('#date_from').val(),
          month_to: $('#date_to').val()
        },
        beforeSend: function(){
            $('body').addClass('wait-pointer');
          },
        complete: function(){
            $('body').removeClass('wait-pointer');
          },
        success: function (data) {  
          $(".content-data-reports-cancelled-shifts").html( data );
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
    });
   
  });
</script>
@endsection 
