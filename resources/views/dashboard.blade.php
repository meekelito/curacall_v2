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
                  @can('view-account-reports')
                    <li class="active" id="accounts-tab"><a data-toggle="tab" onclick="reportAccount(0)"><i class="icon-office position-left"></i> Accounts </a></li>
                  @endcan
                  @can('view-oncall-reports')
                    <li @if(!auth()->user()->can('view-account-reports')) class="active" @endif><a data-toggle="tab" onclick="reportOncall('all','{{ date ( "m/01/Y" ) }} - {{ date ( "m/d/Y" ) }}')"><i class="icon-headset position-left"></i> On call</a></li>
                  @endcan
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

     $.getScripts({
      urls: ["{{ asset('assets/js/plugins/visualization/d3/d3.min.js') }}","{{ asset('assets/js/plugins/visualization/d3/d3_tooltip.js') }}"],
      cache: true,  // Default
      async: false, // Default
      success: function(response) {
          

      }
    });
    
    var myElement = document.getElementById("accounts-tab");
    if(myElement){
      @can('view-account-reports')
        reportAccount();
      @endcan
    }else{
      @can('view-oncall-reports')
        reportOncall('all','{{ date ( "m/01/Y" ) }} - {{ date ( "m/d/Y" ) }}');
      @endcan
    }
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

     var htmlEntities = {
          nbsp: ' ',
          cent: '¢',
          pound: '£',
          yen: '¥',
          euro: '€',
          copy: '©',
          reg: '®',
          lt: '<',
          gt: '>',
          quot: '"',
          amp: '&',
          apos: '\''
      };

      function unescapeHTML(str) {
          return str.replace(/\&([^;]+);/g, function (entity, entityCode) {
              var match;

              if (entityCode in htmlEntities) {
                  return htmlEntities[entityCode];
                  /*eslint no-cond-assign: 0*/
              } else if (match = entityCode.match(/^#x([\da-fA-F]+)$/)) {
                  return String.fromCharCode(parseInt(match[1], 16));
                  /*eslint no-cond-assign: 0*/
              } else if (match = entityCode.match(/^#(\d+)$/)) {
                  return String.fromCharCode(~~match[1]);
              } else {
                  return entity;
              }
          });
      };
 
</script>
@endsection  



