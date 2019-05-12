<div class="container-fluid">
    <div class="row">
        @if( Auth::user()->role_id != 7 )
        <div class="form-group form-group-xs col-sm-3">
            <select class="form-control oncall-user">
                <option value="all">Select On call</option>
                @foreach($users as $row) 
                <option @if($account_id == $row->id) selected  @endif value="{{$row->id}}">{{ $row->fname.' '.$row->lname  }}</option>
                @endforeach
            </select>
        </div>
        @endif 
        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic date-range-val" 
            @if(!empty($range)) value="{{$range}}" 
            @else value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}"
            @endif
            >
        </div>
    </div>

    <div class="row text-center">
        <div class="col-sm-3 active-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#0F56A3;color:#ffffff">
            <div class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#1F2D40">{{ $readAverage }}</div>
            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
             Case Average Time Read
            </div>
        </div>
         <div class="col-sm-3 pending-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#E66E2B;color:#ffffff">
            <div class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#4B332A">{{ $acceptedAverage }}</div>
            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
             Case Average Time Accepted
            </div>
        </div>
        <div class="col-sm-3 pending-case-report" style="border: 5px solid #f5f5f5; padding: 10px;text-align:center;background-color:#04A9F4;color:#ffffff">
            <div class="text-semibold" style="margin: 10px; font-size: 30px;background-color:#1E3E52">{{ $closedAverage }}</div>
            <div class="text-semibold" style="margin: 10px; font-size: 15px;">
             Case Average Time Closed
            </div>
        </div>
    </div>
    <br>
   <!--  <div class="row">
        <div class="col-sm-3 active-case-report" style="border: 5px solid #03a9f4; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Active</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($active_count)
                <a class="btn-active-case">{{$active_count->total}}</a>
              @endisset 
            </span>
        </div>
        <div class="col-sm-3 pending-case-report" style="border: 5px solid #f44336; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Pending</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($pending_count)
                <a class="btn-pending-case">{{$pending_count->total}}</a>
              @endisset 
            </span>
        </div>
        <div class="col-sm-3 closed-case-report" style="border: 5px solid #4caf50; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Closed</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($closed_count)
                <a class="btn-closed-case">{{$closed_count->total}}</a>
              @endisset 
            </span>
        </div>
    </div> -->
    <div class="row text-center">
      <div class="col-sm-9">
        <h6 class="text-semibold no-margin-bottom mt-5">Total Cases</h6>
        <div id="lblTotalCaseRange" class="text-size-small text-muted content-group-sm"></div>

        <div class="svg-center" id="donut_basic_details"></div>
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
  }); 

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
    reportOncall($(".oncall-user" ).val(),$( ".date-range-val" ).val());
  });
  $( ".oncall-user" ).change(function() {
    reportOncall($(this).find(":selected").val(),$( ".date-range-val" ).val());
  });



      $('#lblTotalCaseRange').html($('.date-range-val').val());
      donutWithDetails("#donut_basic_details", 250);

      // Initialize chart


    // Chart setup
    function donutWithDetails(element, size) {


        // Basic setup
        // ------------------------------

        // Add data set
        var data = [
            {
                "status": "Active",
                "icon": "<i class='status-mark border-blue-300 position-left'></i>",
                "value": {{ $active_count->total }},
                "color": "#29B6F6"
            }, {
                "status": "Pending",
                "icon": "<i class='status-mark border-success-300 position-left'></i>",
                "value": {{ $pending_count->total }},
                "color": "#66BB6A"
            }, {
                "status": "Closed",
                "icon": "<i class='status-mark border-danger-300 position-left'></i>",
                "value": {{ $closed_count->total }},
                "color": "#EF5350"
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
</script>