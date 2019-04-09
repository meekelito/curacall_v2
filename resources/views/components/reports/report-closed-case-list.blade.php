<table class="table text-nowrap">
        <thead>
          <tr>
            <th style="width: 50px">Sitting Time</th>
            <th style="width: 150px;">User</th>
            <th>Description</th>
            <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
          </tr>
        </thead>
        <tbody>      
          <tr class="active border-double">
            <td colspan="3">Closed cases</td>
            <td class="text-right">
              <span class="badge bg-success"></span>
            </td>
          </tr>
          @forelse($cases as $row)    
            <tr>
              <td class="text-center">
                <i class="icon-checkmark3 text-success"></i>
              </td>
              <td>
                <div class="media-body">
                  <a href="{{ url('/cases/case_id',$row->id) }}" class="display-inline-block text-default letter-icon-title">{{ $row->sender_fullname }}</a>
                  <div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span> Closed</div>
                </div>
              </td>
              <td>
                <a href="{{ url('/cases/case_id',$row->id) }}" class="text-default display-inline-block">
                  <span>[#{{ $row->case_id }}] Call type</span>
                  <span class="display-block text-muted">Full message of the case...</span>
                </a>
              </td>
              <td class="text-center">
                <span class="text-muted">
                  @if(!empty($row->created_at))
                    @if( date('Y-m-d') == date('Y-m-d', strtotime($row->created_at)))
                        {{  date_format($row->created_at,"h:i a") }}
                    @else
                        {{  date_format($row->created_at,"M d") }}
                    @endif
                  @endif
                </span>
              </td>
            </tr>
          @empty
          <tr class="unread"><td colspan="4">No active case(s) found.</td></tr>
          @endforelse
        </tbody>
      </table>