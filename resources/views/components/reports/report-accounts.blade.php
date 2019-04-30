<div class="container-fluid">
    <div class="row">
        @if( Auth::user()->role_id == 1 )
        <div class="form-group form-group-xs col-sm-3">
            <select class="form-control">
                <option value="all">Select Account</option>
                @foreach($account as $row)
                <option>{{ $row->account_name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic" value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}">
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select class="form-control">
                <option value="all">Select Call type</option>
                <option value="1">Shift Cancelation</option>
                <option value="2">Medical</option>
                <option value="3">Office</option>
                <option value="4">Referral or Agency Contract </option>
                <option value="5">Scheduling</option>
                <option value="6">Contact Request</option>
                <option value="7">Clocking Out / Checkin-Checkout</option>
                <option value="8">Complaints - Shift Related Complaint</option>
                <option value="9">Other</option>
            </select>
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select class="form-control">
                <option value="all">Select Sub-Call Type</option>
                <option value="1">Patient Canceling Shift</option>
                <option value="2">Caregiver Canceling Shift</option>
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
        all_accounts();
    }); 
</script>