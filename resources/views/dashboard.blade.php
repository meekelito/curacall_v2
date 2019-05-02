@extends('layouts.app')
@section('css')
  <style type="text/css">

  </style>
@endsection 
@section('content')
  <div class="page-header page-header-default">
    <div class="page-header-content">
      <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Dashboard</h4>
    </div>
    </div>
    <div class="breadcrumb-line">
      <ul class="breadcrumb">
        <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ul>
    </div>
  </div>

  <div class="content">
  	<div class="panel panel-flat">
        <div class="panel-body">
            <!-- <div class="col-sm-3">
                <ul class="nav">
                    <li class="active"><a href=""><i class="icon-office position-left"></i> Accounts </a></li>
                    <li><a href=""><i class="icon-headset position-left"></i> On call</a></li>
                </ul>
            </div>
            <div class="col-sm-9">
                weeex
            </div> -->


            <div class="tabbable nav-tabs-vertical nav-tabs-left">
                <ul class="nav nav-tabs nav-tabs-highlight" style="width: 200px;">
                    <li class="active"><a data-toggle="tab" onclick="reportAccount(0)"><i class="icon-office position-left"></i> Accounts </a></li>
                    <li><a data-toggle="tab" onclick="reportOncall('all','{{ date ( "m/01/Y" ) }} - {{ date ( "m/d/Y" ) }}')"><i class="icon-headset position-left"></i> On call</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active has-padding content-case">

                    </div>
                </div>
            </div>
        </div>
	</div>
  </div>
@endsection 
@section('script')
<script type="text/javascript">
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-dashboard").addClass('active');

    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default'
    });
    reportAccount();
  }); 

  function reportOncall(id,drange){
    // alert(id+" "+drange);
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-oncall') }}", 
      data: {  
        _token : '{{ csrf_token() }}',
        account_id : id,
        range : drange
      },
      success: function (data) {  
        $(".content-case").html( data );
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
  
  function reportAccount(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('report-account') }}", 
      data: {  
        _token : '{{ csrf_token() }}'
      },
      success: function (data) {  
        $(".content-case").html( data );
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
@endsection  



