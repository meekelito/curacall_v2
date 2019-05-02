@extends('layouts.app')
@section('css')
  <style type="text/css">
  .label{
    border-radius: 20px;
  }
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
      <li class="active">All cases</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="panel panel-flat">
    <div class="table-responsive">
      <table class="table text-nowrap" id="cases-table">
        <thead>
          <tr>
            <th style="width: 50px">Sitting Time</th>
            <th style="width: 150px;">User</th>
            <th style="width: 150px;">Action</th>
            <th>Description</th>
            <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
            <th style="display: none;"></th>
            <th style="display: none;"></th>
          </tr>
        </thead>
        <tbody>
          @php 
          $ctr=1;
          @endphp


          @forelse($cases as $case)


          @php
          $label = "";
          $owner = ""; 
          $recipient = ""; 
          $recipient_name = "";
          @endphp

          @foreach($case['participants'] as $participant)
            @if( $participant['ownership'] == 2)
              @php 
                $label = "accepted";
                $recipient = $participant['fname']." ".$participant['lname'];
                $owner = $participant['fname']." ".$participant['lname'];
              @endphp
            @endif
            @if( $participant['ownership'] == 5)
              @php
                $label = "forwarded";
                $owner = $participant['fname']." ".$participant['lname'];
                $recipient = $case['participants'][0]['fname']." ".$case['participants'][0]['lname'];
              @endphp
            @endif
            @php
              $recipient_name .= $participant['fname'].' '.$participant['lname'].',';
            @endphp
          
          @endforeach


          @switch($case['status'])
              @case(1)
                @if($case['status'] == '1' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="4">Active cases</td>
                  <td class="text-center">
                    <span class="badge bg-blue">{{ $active_count->total }}</span>
                  </td>
                </tr>
                @endif
                <tr>
                  <td class="text-left">
                    @php
                    $datetime1 = new DateTime($case['created_at']);
                    $datetime2 = new DateTime('now');
                    $interval = $datetime1->diff($datetime2);
                    @endphp
                    {{ $interval->format('%ad %hh %im %ss') }}
                  </td>
                  <td>
                    <div class="media-body">
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title">CuraCall</a>
                      <div class="text-muted text-size-small"><span class="status-mark border-blue position-left"></span> Active</div>
                    </div>
                  </td>
                  <td>
                    <div class="media-body">
                      <div class="text-muted text-size-small"><span class="label label-primary">Active</span></div>
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title" title="{{ rtrim($recipient_name,',') }}">{{ $case['participants'][0]['fname']." ".$case['participants'][0]['lname'] }}
                      @if( count($case['participants']) > 1)
                        and {{ count($case['participants'])-1 }} others
                      @endif
                      </a>
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
                  <td style="display: none;">{{ $case['created_at'] }}</td>
                  <th style="display: none;"></th>
                </tr>
                
                @if( $active_count->total == $ctr )
                  @php 
                    $ctr=0;
                  @endphp
                @endif
              @break

              @case(2)
                @if($case['status'] == '2' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="4">Pending cases</td>
                  <td class="text-center">
                    <span class="badge bg-danger">{{ $pending_count->total }}</span>
                  </td>
                </tr>
                @endif
                <tr>
                  <td class="text-left">
                    @php
                    $datetime1 = new DateTime($case['created_at']);
                    $datetime2 = new DateTime('now');
                    $interval = $datetime1->diff($datetime2);
                    @endphp
                    {{ $interval->format('%ad %hh %im %ss') }}
                  </td>
                  <td>
                    <div class="media-body">
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default">{{ $owner }}</a>
                      <div class="text-muted text-size-small"><span class="status-mark border-warning position-left"></span> Pending</div>
                    </div>
                  </td>
                  <td>
                    <div class="media-body">
                      @if($label == "accepted")
                      <div class="text-muted text-size-small"><span class="label label-primary">accepted</span></div>
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title">{{ $recipient }}</a>
                      @else
                      <div class="text-muted text-size-small"><span class="label label-warning">forwarded</span></div>
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title" title="{{ rtrim($recipient_name,',') }}">{{ $recipient }}</a>
                      @if( count($case['participants']) > 1)
                        and {{ count($case['participants'])-1 }} others
                      @endif
                      @endif
                    </div>
                  </td>
                  <td>
                    <a href="{{ url('/cases/case_id',$case['id']) }}" class="text-default display-inline-block">
                      <span>[#{{ $case['case_id'] }}]  Call type</span>
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
                  <td style="display: none;">{{ $case['created_at'] }}</td>
                  <th style="display: none;"></th>
                </tr>

                @if( $pending_count->total == $ctr )
                  @php 
                    $ctr=0;
                  @endphp
                @endif
              @break

              @case(3)
                @if($case['status'] == '3' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="4">Closed cases</td>
                  <td class="text-center">
                    <span class="badge bg-success">{{ $closed_count->total }}</span>
                  </td>
                </tr>
                @endif
                <tr>
                  <td class="text-left">
                    <!-- <i class="icon-checkmark3 text-success"></i> -->
                    @php
                    $datetime1 = new DateTime($case['created_at']);
                    $datetime2 = new DateTime($case['updated_at']);
                    $interval = $datetime1->diff($datetime2);
                    @endphp
                    {{ $interval->format('%ad %hh %im %ss') }}
                  </td>
                  <td>
                    <div class="media-body">
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default letter-icon-title">{{ $owner }}</a>
                      <div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span> Closed</div>
                    </div>
                  </td>
                  <td>
                    <div class="media-body">
                      <div class="text-muted text-size-small"><span class="label label-success">Closed</span></div>
                      <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title" >{{ $owner }}</a>
                    </div>
                  </td>
                  <td>
                    <a href="{{ url('/cases/case_id',$case['id']) }}" class="text-default display-inline-block">
                      <span>[#{{ $case['case_id'] }}] Call type</span>
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
                  <td style="display: none;">{{ $case['created_at'] }}</td>
                  <th style="display: none;">closed</th>
                </tr>
              @break

              @default
                  Default case...
          @endswitch
          @php $ctr++ @endphp
          

          @empty
          <tr class="unread"><td colspan="4">No case(s) found.</td></tr>
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
    $(".submenu-cases-all-cases").addClass('active');

    var table = document.getElementById("cases-table");

    var leng = document.getElementById('cases-table').rows.length-1;
    var td_content = document.getElementById("cases-table").rows[leng].cells[0].innerHTML;
    if( td_content != "No active case(s) found." ){
      var x = setInterval(
      function () {

        for (var i = 2, row; row = table.rows[i]; i++) {
            //iterate through rows
            //rows would be accessed using the "row" variable assigned in the for loop
            if(row.cells[5] != null && row.cells[6].innerHTML != "closed"){


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

        }
      }, 1000);
    }
  });
</script>

@endsection 
