@extends('layouts.app')
<style type="text/css">

</style>
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
            <div class="tabbable nav-tabs-vertical nav-tabs-left">
                <ul class="nav nav-tabs nav-tabs-highlight" style="width: 200px;">
                    <li class="active"><a href="#left-tab1" data-toggle="tab"><i class="icon-office position-left"></i> Accounts </a></li>
                    <li><a href="#left-tab2" data-toggle="tab"><i class="icon-headset position-left"></i> On call</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active has-padding" id="left-tab1">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group form-group-xs col-sm-4">
                                    <select class="form-control">
                                        <option>Select Account</option>
                                        @foreach($account as $row)
                                        <option>{{ $row->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-group-xs col-sm-4">
                                    <input type="text" class="form-control daterange-basic" value="01/01/2019 - {{ date ( 'mm/dd/Y' ) }}">
                                </div>
                            </div>
                        </div>
                        <div class="chart-container">
                          <div class="chart has-fixed-height has-minimum-width" id="rose_diagram_visible"></div>
                        </div>
                    </div>

                    <div class="tab-pane has-padding" id="left-tab2">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group form-group-xs col-sm-4">
                                    <select class="form-control">
                                        <option>Select On call</option>
                                        @foreach($users as $row) 
                                        <option>{{ $row->fname.' '.$row->lname  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-group-xs col-sm-4">
                                    <input type="text" class="form-control daterange-basic" value="{{ date ( 'mm/01/Y' ) }} - {{ date ( 'mm/dd/Y' ) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 active-case-report" style="border: 5px solid #03a9f4; padding: 10px">
                                    <span class="text-semibold" style="margin: 10px; font-size: 15px;">Active</span><br>
                                    <span class="text-semibold" style="margin: 10px; font-size: 30px;">2</span>
                                </div>
                                <div class="col-sm-3 pending-case-report" style="border: 5px solid #f44336; padding: 10px">
                                    <span class="text-semibold" style="margin: 10px; font-size: 15px;">Pending</span><br>
                                    <span class="text-semibold" style="margin: 10px; font-size: 30px;">5</span>
                                </div>
                                <div class="col-sm-3 closed-case-report" style="border: 5px solid #4caf50; padding: 10px">
                                    <span class="text-semibold" style="margin: 10px; font-size: 15px;">Closed</span><br>
                                    <span class="text-semibold" style="margin: 10px; font-size: 30px;">30</span>
                                </div>
                            </div>
                        </div>
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
  }); 

$(function () {

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



            // Charts setup
            // ------------------------------                    

            
            //
            // Nightingale roses with visible labels options
            //

            rose_diagram_visible_options = {

                // Add title
                title: {
                    text: 'All Cases',
                    subtext: 'From January 01, 2019 to Present',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                // legend: {
                //     x: 'left',
                //     y: 'top',
                //     orient: 'vertical',
                //     data: ['A & J Home Care','Ameribest Home Care','Americare New York','Better Home Care','HCS Home Health Care Services of New York','Broadway Healthcare Staffing','Summit Home Healthcare']
                // },

                // Add series
                series: [
                    {
                        name: 'Cases',
                        type: 'pie',
                        radius: ['15%', '73%'],
                        center: ['50%', '57%'],
                        roseType: 'area',

                        // Funnel
                        // width: '40%',
                        // height: '78%',
                        // x: '30%',
                        // y: '17.5%',
                        // max: 150,
                        // sort: 'ascending',

                        data: [
                            {value: 90, name: 'A & J Home Care ( 15.60% )'},
                            {value: 80, name: 'Ameribest Home Care ( 13.86% )'},
                            {value: 70, name: 'Americare New York ( 12.134% )'},
                            {value: 120, name: 'Better Home Care ( 20.80% )'},
                            {value: 66, name: 'HCS Home Health Care Services of New York ( 11.44% )'},
                            {value: 40, name: 'Broadway Healthcare Staffing ( 6.93% )'},
                            {value: 111, name: 'Summit Home Healthcare ( 19.24% )'}
                        ]
                    }
                ]
            };


            

            // Apply options
            // ------------------------------


            rose_diagram_visible.setOption(rose_diagram_visible_options);




            // Resize charts
            // ------------------------------

            window.onresize = function () {
                setTimeout(function (){
                  rose_diagram_visible.resize();
                }, 200);
            }
        }
    );
});

</script>
@endsection  



