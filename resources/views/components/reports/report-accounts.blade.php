<div class="container-fluid">
    <div class="row">
        @if( Auth::user()->role_id == 1 )
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_id" class="form-control" onchange="select_account_report()">
                <option value="all">Select All Account</option>
                @foreach($account as $row)
                <option value="{{ $row->id }}">{{ $row->account_name }}</option>
                @endforeach
            </select>
        </div>
        @else
            <input type="hidden" id="report_account_id" value="{{ Auth::user()->account_id }}">
        @endif
        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic date-range-val" value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}">
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_calltype" class="form-control" onchange="select_account_report();get_subcalltype()">
                <option value="all">Select Call type</option>
                @foreach($calltypes as $row)
                  <option value="{{ $row->name }}">{{ $row->name }}</option>
                @endforeach
              <!--   <option>Shift Cancelation</option>
                <option>Medical</option>
                <option>Office</option>
                <option>Referral or Agency Contract </option>
                <option>Scheduling</option>
                <option>Contact Request</option>
                <option>Clocking Out / Checkin-Checkout</option>
                <option>Complaints - Shift Related Complaint</option>
                <option>Other</option> -->
            </select>
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_subcalltype" class="form-control" onchange="select_account_report()">
                <option value="all">Select Sub-Call Type</option>
                <!-- <option>Payroll</option>
                <option>Patient Canceling Shift</option>
                <option>Caregiver Canceling Shift</option> -->
            </select>
        </div>
    </div>
</div>
<div class="chart-container">
  <div class="chart has-fixed-height has-minimum-width" id="rose_diagram_visible"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.daterange-basic').daterangepicker({
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default'
        });
        select_account_report();
    }); 

      $( ".date-range-val" ).change(function() {
        select_account_report();
      });

      function get_subcalltype()
      {
              $.ajax({ 
                  type: "GET", 
                  url: "{{ route('reports.subcalltypes') }}", 
                  data: {  
                    call_type: $('#report_account_calltype').val()
                  },
                  success: function (data) {  
                        var obj = $.parseJSON(data);
                        $('#report_account_subcalltype').empty().trigger('change');
                        $("#report_account_subcalltype").append("<option value='all'>Select Sub-Call Type</option>");
                      
                        $.each(obj, function(i, item) {
                            $("#report_account_subcalltype").append("<option value='"+item.name+"'>"+item.name+"</option>");
                        });

                       // $('#report_account_subcalltype').trigger('change'); 
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
</script>