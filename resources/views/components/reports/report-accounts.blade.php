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
       var accounts = new Array();
        
         @foreach($account as $row)
          var account = {};
            account.id = "{{ $row->id }}";
            account.name = unescapeHTML("{{ $row->account_name }}");
            accounts.push(account)
         @endforeach

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

    function select_account_report(){ 
    var account_id = $('#report_account_id').val();
     $.ajax({ 
        type: "GET", 
        url: "{{ route('dashboard.cases.count') }}", 
        data: {  
          range: $('.date-range-val').val(),
          account_id: account_id,
          call_type: $('#report_account_calltype').val(),
          subcall_type: $('#report_account_subcalltype').val()
        },
        success: function (data) {  
          //$(".content-case").html( data );
          var chart_data = $.parseJSON(data);
          if(chart_data.length > 0)
          {
            var total_result = 0;
            $.each( chart_data, function( key, value ) {
              total_result += value.value;
            });

            $.each( chart_data, function( key, value ) {
              var percentage = (value.value/total_result) * 100;
              value.name = value.name + " (" + percentage.toPrecision(3) +"%) : " + value.value;
            });

         //console.log($('.date-range-val').val());
             // Set paths
            // ------------------------------
            require.config({
                paths: {
                    echarts: "{{ asset('assets/js/plugins/visualization/echarts') }}"
                } 
            });
            // Configuration
            // ------------------------------
            require(
                [
                    'echarts',
                    'echarts/theme/limitless',
                    'echarts/chart/pie',
                    'echarts/chart/funnel'
                ],
                // Charts setup
                function (ec, limitless) {
                    // Initialize charts
                    // ------------------------------
                    var rose_diagram_visible = ec.init(document.getElementById('rose_diagram_visible'), limitless);

                    rose_diagram_visible_options = {
                        // Add title
                        title: {
                            text: 'Total Cases: ' + ' (' + total_result.toLocaleString() + ')',
                            subtext: $('.date-range-val').val(),
                            x: 'center'
                        },
                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            formatter: "{a} <br/>{b}"
                            // formatter: "{a} <br/>{b}: {c} ({d}%)"
                        },
                        // Add series
                        series: [
                            {
                                name: 'Cases',
                                type: 'pie',
                                radius: ['15%', '73%'],
                                center: ['50%', '57%'],
                                roseType: 'area',

                                data: chart_data
                            }
                        ]
                    };
                    rose_diagram_visible.hideLoading();
                    rose_diagram_visible.setOption(rose_diagram_visible_options);
                    rose_diagram_visible.on('click', function (params) {
                      var obj_name = params.name.replace(/ *\([^)]*\) */g, ""); // remove (30%)
                     
                      var parameter_Start_index= obj_name.indexOf(':');
                      var obj_name = obj_name.substring(0, parameter_Start_index); // remove : 31
                       @if( Auth::user()->role_id == 1 )
                            if($('#report_account_id').val() == "all"){

                              var acct = accounts.find(account => account.name === unescapeHTML(obj_name));
                             
                              $('#report_account_id').val(acct.id).trigger('change');
                            
                            }
                       @endif

                      if($('#report_account_calltype').val() == "all"){
                            if($('#report_account_calltype option').filter(function(){ return $(this).val() == obj_name; }).length){
                                 $('#report_account_calltype').val(obj_name).trigger('change');
                                
                            }
                      }

                      if($('#report_account_subcalltype').val() == "all"){
                            if($('#report_account_subcalltype option').filter(function(){ return $(this).val() == obj_name; }).length){
                                 $('#report_account_subcalltype').val(obj_name).trigger('change');
                                
                            }
                      }

                    });
                    // Resize charts
                    // ------------------------------

                    // window.onresize = function () {
                    //     setTimeout(function (){
                    //       rose_diagram_visible.resize();
                    //     }, 200);
                    // }
                }
            );
          }else{
            $('#rose_diagram_visible').html('No result');
          }
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