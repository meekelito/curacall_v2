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
    <tr class="active border-double">
      <td colspan="4">Active cases</td>
      <td class="text-center">
      </td>
    </tr>

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
      
      @endforeach
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
      </tr>
    @empty
      <tr class="unread"><td colspan="4">No case(s) found.</td></tr>
    @endforelse
  </tbody>
</table>