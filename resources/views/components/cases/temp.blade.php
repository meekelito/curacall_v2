<div class="row">
  <div class="col-lg-8">
    <div class="panel panel-flat">
      <div class="panel-toolbar panel-toolbar-inbox">
        <div class="navbar navbar-default">
          <ul class="nav navbar-nav visible-xs-block no-border">
            <li>
              <a class="text-center collapsed" data-toggle="collapse" data-target="#inbox-toolbar-toggle-single">
                <i class="icon-circle-down2"></i>
              </a>
            </li>
          </ul>

          <div class="navbar-collapse collapse" id="inbox-toolbar-toggle-single">
            
            <div class="btn-group navbar-btn">
               <div id="case_active" @if( $case_info[0]->status == 1 && ($participation[0]->ownership == 1 || $participation[0]->ownership == 2) ) class="btn-group show" @else class="btn-group hidden" @endif>
                <a class="btn btn-primary btn-accept"><i class="icon-thumbs-up3"></i> <span class="hidden-xs position-right">Accept</span></a>
                <a class="btn btn-warning btn-decline"><i class="icon-thumbs-down3"></i> <span class="hidden-xs position-right">Decline</span></a>
              </div>
              <div id="case_open" @if( $case_info[0]->status == 2 && $participation[0]->ownership == 3 ) class="btn-group show" @else class="btn-group hidden" @endif>
                <a class="btn btn-default btn-forward"><i class="icon-forward"></i> <span class="hidden-xs position-right">Forward</span></a>
                <a class="btn btn-default btn-close"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Close</span></a>
              </div>
              <div id="case_closed" @if( $case_info[0]->status == 3 ) class="btn-group show" @else class="btn-group hidden" @endif>
                <a class="btn btn-default btn-reopen"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Re-Open</span></a>
              </div>

              <div style="margin:0; padding-top: 10px !important;" id="case_owner" @if( $case_info[0]->status == 2 && ($participation[0]->ownership == 4 || $participation[0]->ownership == 5 ) ) class="btn-group show" @else class="btn-group hidden" @endif>

                @foreach($participants as $row)
                  @if( $row->ownership == 3 )
                   Assigned to :  <span class="text-semibold">{{ ucwords($row->fname.' '.$row->lname) }}</span>
                  @endif
                @endforeach
          
              </div>
            </div>
            <div class="pull-right-lg">
              <p class="navbar-text">
                @if(!empty($case_info[0]->created_at ))
                  {{  date_format($case_info[0]->created_at,"M d,Y  h:i a") }}
                @endif
              </p>
              <div class="btn-group navbar-btn">
                <a class="btn btn-default"><i class="icon-printer"></i> <span class="hidden-xs position-right">PDF</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="height: 600px !important; overflow-y: scroll;">
      <table class="table"> 
        <tr class="active"><td colspan="2">Caller Information</td></tr>
        <tr><td width="200">First Name:</td><td>Tara</td></tr>
        <tr><td>Last Name:</td><td>Davis</td></tr>
        <tr><td>Caller Type:</td><td>Caregiver</td></tr>
        <tr class="active"><td colspan="2">Type of Caregiver Home Health Aide (HHA)</td></tr>
        <tr><td>Caller Telephone Number:</td><td>212-098-7654</td></tr>
        <tr><td>Is Hospital Related:</td><td>No</td></tr>
        <tr><td>Is Clock-in Code Available:</td><td>Does Not Know</td></tr>
        <tr class="active"><td colspan="2">Call Information</td></tr>
        <tr><td>Call Type:</td><td>Office; Payroll</td></tr>
        <tr><td>Payroll concern:</td><td>Was not paid Correct Amount</td></tr>
        <tr><td>Action:</td><td>Take a Message and Send Email</td></tr>
        <tr class="active"><td colspan="2">Caregiver Information</td></tr>
        <tr><td>Type of Caregiver:</td><td>Home Health Aide (HHA)</td></tr>
        <tr><td>First Name:</td><td>Tara</td></tr>
        <tr><td>Last Name:</td><td>Davis</td></tr>
        <tr><td>Telephone Number:</td><td>212-098-7654</td></tr>
        <tr><td>Provide start time of shift:</td><td>Not Applicable</td></tr>
        <tr class="active"><td colspan="2">Other Information</td></tr>
        <tr><td>Full Message:</td><td>HHA called and wants to speak with Payroll in regards to incorrect amount on her paycheck Please give her a call back as soon as possible as she is currently in the bank.</td></tr>
        <tr><td>Call Language:</td><td>Russian</td></tr>
        <tr><td>Contact Translation Company:</td><td>Yes</td></tr>
        <tr><td>Number of Calls:</td><td>1st Time</td></tr>
        <tr class="active"><td colspan="2">Case Create</td></tr>
        <tr><td>Date/Time:</td><td>11/30/2018 02:27 PM</td></tr>
        <tr><td>Created By:</td><td>Kristina Valerio</td></tr>
        <tr><td>Case Sent Date/Time:</td><td>11/30/2018 02:37 PM</td></tr>
      </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">Notes</h5>
        <div class="heading-elements">
          <div class="btn-group navbar-btn">
            <button type="button" class="btn btn-primary btn-icon btn-rounded btn-sm btn-add-note" title="Add note(s)"><i class="icon-plus3"></i></button>
          </div>
        </div>
      </div>

      <table class="table table-borderless" id="tbl-notes" width="100%" style="position:relative; top:-30px;">
        
        <tbody>
        </tbody>
      </table>

    </div>
  </div>

  @if($participants->count() > 1)
  <div class="col-lg-4">
    <!-- Collapsible list -->
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">Participants</h5>
      </div>
      <ul class="media-list media-list-linked">
        @foreach($participants as $row)
        <li class="media">
          <div class="media-link cursor-pointer" data-toggle="collapse" data-target="#{{$row->id}}">
            <div class="media-left"><img @if( $row->ownership == 3 )style="border: 2px solid #2196f3;"@endif src="{{ asset('storage/uploads/users/'.$row->prof_img.'?v='.strtotime('now')) }}" class="img-circle img-md" alt=""></div>
            <div class="media-body">
              <div class="media-heading text-semibold">
              {{ ucwords($row->fname.' '.$row->lname) }}
              </div>
              <span class="text-muted">{{ ucwords($row->title) }}</span>
            </div>
            <div class="media-right media-middle text-nowrap">
              <i class="icon-menu7 display-block"></i>
            </div>
          </div>

          <div class="collapse" id="{{$row->id}}">
            <div class="contact-details">
              <ul class="list-extended list-unstyled list-icons">
                <li><i class="icon-pin position-left"></i> Amsterdam</li>
                <li><i class="icon-phone position-left"></i> {{ $row->phone_no }}</li>
                <li><i class="icon-mail5 position-left"></i> <a href="#">{{ $row->email }}</a></li>
              </ul>
            </div>
          </div>
        </li>
        @endforeach
        
      </ul>
    </div>
    <!-- /collapsible list -->
  </div>
  @endif
</div>