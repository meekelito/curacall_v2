@extends('layouts.app')
@section('css')
  <style type="text/css">

  </style>
@endsection 

@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cases</span> - List</h4>
    </div>

    <div class="heading-elements">
      <form class="heading-form" action="#">
        <div class="form-group">
          <div class="has-feedback">
            <input type="search" class="form-control" placeholder="Search cases">
            <div class="form-control-feedback">
              <i class="icon-search4 text-size-small text-muted"></i>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li><a href="#l"><i class="icon-home2 position-left"></i> Home</a></li>
      <li><a href="#">Cases</a></li>
      <li class="active">Active cases</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="panel panel-flat">
    <div class="table-responsive">
      <table class="table text-nowrap" id="active-table">
        <thead>
          <tr>
            <th style="width: 50px">Sitting Time</th>
            <th style="width: 150px;">User</th>
            <th style="width: 150px;">Action</th>
            <th>Description</th>
            <th class="text-center" style="width: 150px;"><i class="icon-arrow-down12"></i></th>
            <th style="display: none;"></th>
          </tr>
        </thead>
        <tbody>      
          <tr class="active border-double">
            <td colspan="4">Active cases</td>
            <td class="text-right">
              <span class="badge bg-blue">{{ $active_count->total }}</span>
            </td>
          </tr>
          @forelse($cases as $case)  
            @php
              $sender = "CuraCall";
              $action = "";
            @endphp
            @foreach($case['participants'] as $participant)  
              @if( $participant['ownership'] == 5 )
                $sender = $participant['fname'].' '.$participant['lname'];
              @endif

              @if( $participant['ownership'] == 2 )
                $action = $participant['fname'].' '.$participant['lname'];
              @endif
            @endforeach
            <tr>
              <td class="text-center">
       
              </td>
              <td>
                <div class="media-body">
                  <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title">{{ $sender }}</a>
                  <div class="text-muted text-size-small"><span class="status-mark border-blue position-left"></span> Active</div>
                </div>
              </td>
              <td>
                <div class="media-body">
                  <div class="text-muted text-size-small"><span class="label label-warning">forwarded</span></div>
                  @foreach($case['participants'] as $participant)
                  <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title">{{ $participant['fname'].' '.$participant['lname'] }}</a>
                  @endforeach
                </div>
              </td>
              <td>
                <a href="{{ url('/cases/case_id',$case['id']) }}" class="text-default display-inline-block">
                  <span class="text-semibold">[#{{ $case['case_id'] }}] Call type</span>
                  <span class="display-block text-muted">Full message of the case...</span>
                </a>
              </td>
              <td class="text-left">
                <span class="text-muted">
                  @if(!empty($case['created_at']))
                    {{  date_format($case['created_at'],"M d,Y") }}<br>
                    {{  date_format($case['created_at'],"h:i a") }}
                  @endif
                </span>
              </td>
              <td class="text-center" style="display: none;">
                @if(!empty($case['created_at']))
                {{ $case['created_at'] }}
                @endif
              </td>
            </tr>
          @empty
          <tr class="unread" id="unread"><td colspan="4">No active case(s) found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection  

@section('script')
<script type="text/javascript">
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-cases").addClass('active');
    $(".submenu-curacall li").removeClass("active");
    $(".submenu-cases-active-cases").addClass('active');
    var table = document.getElementById("active-table");

    var leng = document.getElementById('active-table').rows.length-1;
    var td_content = document.getElementById("active-table").rows[leng].cells[0].innerHTML;
    if( td_content != "No active case(s) found." ){
      var x = setInterval(
      function () {

        for (var i = 2, row; row = table.rows[i]; i++) {
            //iterate through rows
            //rows would be accessed using the "row" variable assigned in the for loop
            var endDate = row.cells[5];
            var countDownDate = new Date(endDate.innerHTML).getTime();
            var countDown = row.cells[0];
            // Update the count down every 1 second

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now an the count down date
            var distance = now - countDownDate;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element
            countDown.innerHTML = (days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ");

        }
      }, 1000);
    }
  });
</script>

@endsection 
