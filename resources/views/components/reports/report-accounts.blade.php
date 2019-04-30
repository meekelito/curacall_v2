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
        @endif
        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic date-range-val" value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}">
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_calltype" class="form-control" onchange="select_account_report()">
                <option value="all">Select Call type</option>
                <option>Shift Cancelation</option>
                <option>Medical</option>
                <option>Office</option>
                <option>Referral or Agency Contract </option>
                <option>Scheduling</option>
                <option>Contact Request</option>
                <option>Clocking Out / Checkin-Checkout</option>
                <option>Complaints - Shift Related Complaint</option>
                <option>Other</option>
            </select>
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_subcalltype" class="form-control" onchange="select_account_report()">
                <option value="all">Select Sub-Call Type</option>
                <option>Payroll</option>
                <option>Patient Canceling Shift</option>
                <option>Caregiver Canceling Shift</option>
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
</script>