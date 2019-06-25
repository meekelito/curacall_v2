@extends('layouts.app')

@section('content')
  <!-- Page header -->
  <div class="page-header page-header-default">
      <div class="page-header-content">
          <div class="page-title">
              <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Reports</span> - Billing</h4>
          </div>
      </div>
      <div class="breadcrumb-line">
          <ul class="breadcrumb">
              <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
              <li class="active">Billing</li>
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
          <div class="col-sm-3">
            <input type="month" class="form-control" name="billing_month" id="billing_month" required>
          </div>
          <div class="col-sm-1">
            <div class="checkbox">
              <label>
                <input type="checkbox" class="styled" name="is_role">
                View by role
              </label>
            </div>
          </div>
          <button type="button" class="btn btn-primary btn-icon btn-search"><i class="icon-search4"></i></button>
        </div>
        <div class="row">
          <div class="col-sm-12 content-data-reports-billing">
            <table class="table" style="margin-top: 40px;">
              <tr class="bg-primary">
                <td>Account</td>
                <td>Active User</td>
                <td>Date Activated</td>
                <td>User Role</td>
                <td>[month] Prorate Users</td>
                <td>[month] Billing Prorate</td>
                <td>[month] Prorate Users</td>
                <td>[month] Billing</td>
                <td>Total</td>
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
    $(".menu-reports-billing").addClass('active');
    
    
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
