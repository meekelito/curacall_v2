<div class="container-fluid">
    <div class="row">
        @role('agency-coordinators')
         <input type="hidden" class="oncall-user" value="{{ Auth::user()->id }}" />
        @else
        <div class="form-group form-group-xs col-sm-3">
            <select class="select-search oncall-user" style="width: 100% !important;">
                <option value="all">Select On call</option>
                @foreach($users as $row) 
                <option @if($account_id == $row->id) selected  @endif value="{{$row->id}}">{{ $row->fname.' '.$row->lname  }}</option>
                @endforeach
            </select>
        </div>
        @endrole

        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic date-range-val" 
            @if(!empty($range)) value="{{$range}}" 
            @else value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}"
            @endif
            >
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
                    <div class="row text-center">
                        <div class="col-sm-3 active-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#03A9F4;color:#ffffff">
                            <div id="readAverage" class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#1F2D40"></div>
                            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
                             Case Average Time Read
                            </div>
                        </div>
                         <div class="col-sm-3 pending-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#F44336;color:#ffffff">
                            <div id="acceptedAverage" class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#4B332A"></div>
                            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
                             Case Average Time Accepted
                            </div>
                        </div>
                        <div class="col-sm-3 pending-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#4CAF50;color:#ffffff">
                            <div id="closedAverage" class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#1E3E52"></div>
                            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
                             Case Average Time Closed
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                      <div class="col-sm-9">
                        <h6 class="text-semibold no-margin-bottom mt-5">Total Cases</h6>
                        <div id="lblTotalCaseRange" class="text-size-small text-muted content-group-sm"></div>

                        <div class="svg-center" id="donut_basic_details"></div>
                      </div>
                    </div>
            </div>
            <div class="tab-pane" id="highlighted-justified-tab2">
              <div class="row form-inline text-center">

                  <div class="form-group" style="width:150px;margin: 0 auto;">
                    <label class="" for="email">Chart:</label>
                    <select onchange="oncall_trend_report()" id="chart_type" class="form-control input-xs">
                      
                        <option>line</option>
                        <option>bar</option>

                      </select>
                  </div>
           
                  <div class="form-group" style="width:150px;margin: 0 auto;">
                    <label class="" for="email">Year:</label>
                    <select onchange="oncall_trend_report()" id="trend_year" class="form-control input-xs">
                        <?php $year = date('Y'); ?>
                        @for($x=0;$x < 5;$x++)
                            <option>{{ $year - $x }}</option>
                        @endfor
                     <!--    <option>2019</option>
                        <option>2018</option>
                        <option>2017</option> -->
                      </select>
                  </div>
               
              </div>
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

    getOverallAverage();
    getOverallCaseStatus();
    oncall_trend_report();
    $('.select-search').select2();
  }); 

  var stacked_lines;
  var stacked_lines_options;

  function report_case_list(status)
  {
    var url = "";
    if(status == "Active"){
      url = "{{ url('report-active-case-list') }}";
    }
    else if(status == "Pending"){
      url = "{{ url('report-pending-case-list') }}";
    }
    else{
      url = "{{ url('report-closed-case-list') }}";
    }

      $.ajax({  
      type: "POST", 
      url: url, 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val(),
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

  $( ".btn-active-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-active-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
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
  });

  $( ".btn-pending-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-pending-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
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
  });

  $( ".btn-closed-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-closed-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
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
  });

  $( ".date-range-val" ).change(function() {
    getOverallAverage();
    getOverallCaseStatus();
    //reportOncall($(".oncall-user" ).val(),$( ".date-range-val" ).val());
  });
  $( ".oncall-user" ).change(function() {
    getOverallAverage();
    getOverallCaseStatus();
    oncall_trend_report();
    //reportOncall($(this).find(":selected").val(),$( ".date-range-val" ).val());
  });

  function getOverallAverage()
  {
     $(".content-case").block({
        message: '<i style="font-size: 30px;" class="icon-spinner2 spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none'
        }
      });
     $.ajax({  
      type: "POST", 
      url: "{{ route('report.overall-average') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        account_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
      },
      success: function (data) {  
        var result = $.parseJSON(data);
        $('#readAverage').html(result.read);
        $('#acceptedAverage').html(result.accepted);
        $('#closedAverage').html(result.closed);
      },
      error: function (data){
        swal({
          title: "Oops..!",
          text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
          confirmButtonColor: "#EF5350",
          type: "error"
        });
      },
      complete: function()
      {
        $(".content-case").unblock();
      }
    });
  }

  function getOverallCaseStatus()
  {

     $.ajax({  
      type: "POST", 
      url: "{{ route('report.overall-case-status') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        account_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
      },
      success: function (data) {  
        var result = $.parseJSON(data);
        // $('#readAverage').html(result.read);
        // $('#acceptedAverage').html(result.accepted);
        // $('#closedAverage').html(result.closed);
          $('#donut_basic_details').html('');
             donutWithDetails("#donut_basic_details", 250,result);
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


      $('#lblTotalCaseRange').html($('.date-range-val').val());
 

      // Initialize chart


    // Chart setup
    function donutWithDetails(element, size,count) {


        // Basic setup
        // ------------------------------

        // Add data set
        var data = [
            {
                "status": "Active",
                "icon": "<i class='status-mark border-blue-300 position-left'></i>",
                "value": count.active,
                "color": "#03A9F4"
            }, {
                "status": "Pending",
                "icon": "<i class='status-mark border-success-300 position-left'></i>",
                "value": count.pending,
                "color": "#F44336"
            }, {
                "status": "Closed",
                "icon": "<i class='status-mark border-danger-300 position-left'></i>",
                "value": count.closed,
                "color": "#4CAF50"
            }
        ];

        // Main variables
        var d3Container = d3.select(element),
            distance = 2, // reserve 2px space for mouseover arc moving
            radius = (size/2) - distance,
            sum = d3.sum(data, function(d) { return d.value; });


        // Tooltip
        // ------------------------------

        var tip = d3.tip()
            .attr('class', 'd3-tip')
            .offset([-10, 0])
            .direction('e')
            .html(function (d) {
                return "<ul class='list-unstyled mb-5'>" +
                    "<li>" + "<div class='text-size-base mb-5 mt-5'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                    "<li>" + "Total: &nbsp;" + "<span class='text-semibold pull-right'>" + d.value + "</span>" + "</li>" +
                    "<li>" + "Share: &nbsp;" + "<span class='text-semibold pull-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                "</ul>";
            });


        // Create chart
        // ------------------------------

        // Add svg element
        var container = d3Container.append("svg").call(tip);
        
        // Add SVG group
        var svg = container
            .attr("width", size)
            .attr("height", size)
            .append("g")
                .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


        // Construct chart layout
        // ------------------------------

        // Pie
        var pie = d3.layout.pie()
            .sort(null)
            .startAngle(Math.PI)
            .endAngle(3 * Math.PI)
            .value(function (d) { 
                return d.value;
            }); 

        // Arc
        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius / 1.35);


        //
        // Append chart elements
        //

        // Group chart elements
        var arcGroup = svg.selectAll(".d3-arc")
            .data(pie(data))
            .enter()
            .append("g") 
                .attr("class", "d3-arc")
                .style({
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'cursor': 'pointer'
                });
        
        // Append path
        var arcPath = arcGroup
            .append("path")
            .style("fill", function (d) {
                return d.data.color;
            });


        //
        // Add interactions
        //

        // Mouse
        arcPath
            .on('mouseover', function(d, i) {

                // Transition on mouseover
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('elastic')
                    .attr('transform', function (d) {
                        d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                        var x = Math.sin(d.midAngle) * distance;
                        var y = -Math.cos(d.midAngle) * distance;
                        return 'translate(' + x + ',' + y + ')';
                    });

                $(element + ' [data-slice]').css({
                    'opacity': 0.3,
                    'transition': 'all ease-in-out 0.15s'
                });
                $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
            })
            .on('mouseout', function(d, i) {

                // Mouseout transition
                d3.select(this)
                .transition()
                    .duration(500)
                    .ease('bounce')
                    .attr('transform', 'translate(0,0)');

                $(element + ' [data-slice]').css('opacity', 1);
            }) .on('click', function(d, i) {
                //console.log(d.data.status);
                report_case_list(d.data.status);
            });

        // Animate chart on load
        arcPath
            .transition()
            .delay(function(d, i) {
                return i * 500;
            })
            .duration(500)
            .attrTween("d", function(d) {
                var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                return function(t) {
                    d.endAngle = interpolate(t);
                    return arc(d);  
                }; 
            });


        //
        // Add text
        //

        // Total
        svg.append('text')
            .attr('class', 'text-muted')
            .attr({
                'class': 'half-donut-total',
                'text-anchor': 'middle',
                'dy': -13
            })
            .style({
                'font-size': '12px',
                'fill': '#999'
            })
            .text('Total');

        // Count
        svg
            .append('text')
            .attr('class', 'half-donut-count')
            .attr('text-anchor', 'middle')
            .attr('dy', 14)
            .style({
                'font-size': '21px',
                'font-weight': 500
            });

        // Animate count
        svg.select('.half-donut-count')
            .transition()
            .duration(1500)
            .ease('linear')
            .tween("text", function(d) {
                var i = d3.interpolate(this.textContent, sum);

                return function(t) {
                    this.textContent = d3.format(",d")(Math.round(i(t)));
                };
            });


        //
        // Add legend
        //

        // Append list
        var legend = d3.select(element)
            .append('ul')
            .attr('class', 'chart-widget-legend')
            .selectAll('li')
            .data(pie(data))
            .enter()
            .append('li')
            .attr('data-slice', function(d, i) {
                return i;
            })
            .attr('style', function(d, i) {
                return 'border-bottom: solid 2px ' + d.data.color;
            })
            .text(function(d, i) {
                return d.data.status + ': ';
            });

        // Append text
        legend.append('span')
            .text(function(d, i) {
                return d.data.value;
            });
    }

    $( "#tab-trend a" ).on( "click", function() {

      if(stacked_lines == undefined)
        return;

       setTimeout(function () {
          stacked_lines.resize();
          load_line_chart();
        }, 10);
    });

    function oncall_trend_report()
    {
        $(".content-case").block({
          message: '<i style="font-size: 30px;" class="icon-spinner2 spinner"></i>',
          overlayCSS: {
              backgroundColor: '#fff',
              opacity: 0.8,
              cursor: 'wait'
          },
          css: {
              border: 0,
              padding: 0,
              backgroundColor: 'none'
          }
        });
        $.ajax({ 
        type: "GET", 
        url: "{{ route('report.oncall.chart.trend') }}", 
        data: {  
          year: $('#trend_year').val(),
          user_id : $(".oncall-user").val(),
          chart: $('#chart_type').val()
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
                    'echarts/chart/bar',
                    'echarts/chart/line'
                ],
                // Charts setup
                function (ec, limitless) {


                    // line chart
                   stacked_lines  = ec.init(document.getElementById('stacked_lines'), limitless);

                        stacked_lines_options = {
                            color:chart_data.accounts.color,
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
                                data: chart_data.accounts.name,
                               
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
        },
        complete: function()
        {
          $(".content-case").unblock();
        }
      });  
    }

    function load_line_chart()
    {
         stacked_lines.setOption(stacked_lines_options);
    }
</script>