<div class="container-fluid">
  
    <div class="row">
        @if( Auth::user()->role_id == 1 )
        <div class="form-group form-group-xs col-sm-3">
            <!-- <select id="report_account_id" class="form-control" onchange="select_account_report()"> -->
            <select class="select-search" id="report_account_id" onchange="select_account_report()" style="width: 100% !important;">
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
            </select>
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <select id="report_account_subcalltype" class="form-control" onchange="select_account_report()">
                <option value="all">Select Sub-Call Type</option>
            </select>
        </div>
    </div>

    <div class="row">
     <div class="tabbable">
          <ul class="nav nav-tabs nav-tabs-highlight">
            <li id="tab-overall" class="active"><a href="#highlighted-justified-tab1" data-toggle="tab">Overall</a></li>
            <li id="tab-trend"><a href="#highlighted-justified-tab2" data-toggle="tab">Trend</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="highlighted-justified-tab1">        
                   <div class="row chart-container">
                      <div class="chart has-fixed-height has-minimum-width" id="rose_diagram_visible" style="width: 100% !important; min-height: 400px"></div>
                    </div>
            </div>
            <div class="tab-pane" id="highlighted-justified-tab2">
                <!-- <div style="display:block;width:150px;margin: 0 auto;">
                  <span>Year</span>
       
                    <select class="form-control input-xs">
                      <option>2019</option>
                      <option>2018</option>
                      <option>2017</option>
                    </select>
              
                </div> -->
                <div class="form-inline" style="width:150px;margin: 0 auto;">
                <div class="form-group">
                  <label class="" for="email">Year:</label>
                  <select onchange="account_trend_report()" id="trend_year" class="form-control input-xs">
                      <?php $year = date('Y'); ?>
                      @for($x=0;$x < 5;$x++)
                          <option>{{ $year - $x }}</option>
                      @endfor
                   <!--    <option>2019</option>
                      <option>2018</option>
                      <option>2017</option> -->
                    </select>
                </div></div>
                <div class="chart-container">
                  <div class="chart has-fixed-height" id="stacked_lines" style="width: 100% !important; min-height: 400px"></div>
                </div>
            </div>
          </div>
        </div>
    </div>

     <div class="row content-list-table">
      
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.daterange-basic').daterangepicker({
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default'
        });

        select_account_report();
        $('.select-search').select2();
    }); 
     var stacked_lines;
     var rose_diagram_visible;
     var rose_diagram_visible_options;
     var stacked_lines_options;

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

      function report_by_calltypes()
      {

          $.ajax({  
          type: "POST", 
          url: "{{ url('report-by-calltypes') }}", 
          data: {   
            _token : '{{ csrf_token() }}',
            account_id : $("#report_account_id").val(),
            range : $(".date-range-val").val(),
            call_type: $('#report_account_calltype').val(),
            subcall_type: $('#report_account_subcalltype').val()
          },
          success: function (data) {  
            $(".content-list-table").html( data );
            // alert(data);
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

    function account_trend_report()
    {
        $.ajax({ 
        type: "GET", 
        url: "{{ route('report.chart.trend') }}", 
        data: {  
          year: $('#trend_year').val(),
          account_id: $('#report_account_id').val(),
          call_type: $('#report_account_calltype').val(),
          subcall_type: $('#report_account_subcalltype').val()
        },
        success: function (data) {  
          //$(".content-case").html( data );
          var chart_data = $.parseJSON(data);
          if(chart_data.data.length > 0)
         {
  

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
                    'echarts/chart/line'
                ],
                // Charts setup
                function (ec, limitless) {


                    // line chart
                   stacked_lines  = ec.init(document.getElementById('stacked_lines'), limitless);

                        stacked_lines_options = {

                            // Setup grid
                            grid: {
                                x: 40,
                                x2: 20,
                                y: 135,
                                y2: 25
                            },

                            // Add tooltip
                            tooltip: {
                                trigger: 'item'
                            },

                            // Add legend
                            legend: {
                                data: chart_data.accounts,
                               
                            },

                            // Enable drag recalculate
                            //calculable: true,

                            // Hirozontal axis
                            xAxis: [{
                                type: 'category',
                                boundaryGap: false,
                                data: [
                                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'
                                ]
                            }],

                            // Vertical axis
                            yAxis: [{
                                type: 'value'
                            }],

                            // Add series
                            series: chart_data.data
                        };

                         setTimeout(function () {
                            load_line_chart();
                        }, 200);

                             window.onresize = function () {
                              setTimeout(function () {
                                  stacked_lines.resize();
                              }, 200);
                          }                      
                      
                }
            );
          }else{
            $('#stacked_lines').html('No result');
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

    function select_account_report(){
                          report_by_calltypes(); 
    var account_id = $('#report_account_id').val();
     $.ajax({ 
        type: "GET", 
        url: "{{ route('report.chart.overall') }}", 
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
                    rose_diagram_visible = ec.init(document.getElementById('rose_diagram_visible'), limitless);

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

                    load_rose_chart();

                             window.onresize = function () {
                              setTimeout(function () {
                                  rose_diagram_visible.resize();
                              }, 200);
                          }                      
                      
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

    account_trend_report();
}
    $( "#tab-overall a" ).on( "click", function() {
      if(rose_diagram_visible == undefined)
        return;
      
        setTimeout(function () {
           rose_diagram_visible.resize();
           load_rose_chart();
        }, 10);
    });

    $( "#tab-trend a" ).on( "click", function() {

      if(stacked_lines == undefined)
        return;

       setTimeout(function () {
          stacked_lines.resize();
          load_line_chart();
        }, 10);
    });

    function load_rose_chart()
    {
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
    }

    function load_line_chart()
    {
         stacked_lines.setOption(stacked_lines_options);
    }
</script>