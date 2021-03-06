<table class="table text-nowrap" id="cases-table">
  <thead>
    <tr>
      <th style="width: 50px">Sitting Time</th>
      <th style="width: 150px;">User</th>
      <th style="width: 150px;">Action</th>
      <th>Description</th>
      <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
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
    $is_read = 0;
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

      @if($participant['user_id'] == Auth::user()->id && $participant['is_read'] == 1)
        @php
        $is_read = 1;
        @endphp
      @endif
    
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
                <div class="text-muted text-size-small"><span class="status-mark border-blue position-left @if(!$is_read) bg-blue @endif"></span> Active</div>
              </div>
            </td>
            <td>
              <div class="media-body">
                <div class="text-muted text-size-small"><span class="label label-primary">Active</span></div>
                <a href="{{ url('/cases/case_id',$case['id']) }}" class="display-inline-block text-default text-semibold letter-icon-title" title="{{ rtrim($recipient_name,',') }}">@if(isset($case['participants'][0])){{ $case['participants'][0]['fname']." ".$case['participants'][0]['lname'] }}@endif
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
                <div class="text-muted text-size-small"><span class="status-mark border-warning position-left @if(!$is_read) bg-warning @endif"></span> Pending</div>
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
