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
      <div class="navbar-collapse collapse">
        
          <div class="btn-group navbar-btn">
            @if( ($case_info[0]->status == 1) )
            <span class="label border-left-primary label-striped">Active</span>
            @endif
            @if( ($case_info[0]->status == 2) )
            <span class="label border-left-warning label-striped">Pending</span>
            @endif
            @if( ($case_info[0]->status == 3) )
            <span class="label border-left-success label-striped">Closed</span>
            @endif
          </div>
        @if( $is_reviewed == 1 && auth()->user()->hasRole('curacall-admin'))
          @if($case_info[0]->is_reviewed == 0)
          <div class="btn-group navbar-btn">
            <a class="btn btn-primary btn-reviewed"><i class="icon-checkmark2"></i> <span class="hidden-xs position-right">Reviewed</span></a>
          </div>
          @else
          <div class="btn-group navbar-btn">
            <span class="label border-left-success label-striped">Reviewed</span>
          </div>
          @endif
        @else

          @if( isset($participation[0]->ownership) )
            @php $ownership = $participation[0]->ownership; @endphp
          @else
            @php $ownership = 0; @endphp
          @endif

          @if($ownership == 0 && auth()->user()->can('pull-case'))
          <div class="btn-group navbar-btn">
            <a class="btn btn-primary btn-pull"><i class="icon-file-download"></i> <span class="hidden-xs position-right">Pull case</span></a>
          </div>
          @endif


          @if( ($case_info[0]->status == 1 && $ownership == 1) || ($case_info[0]->status == 2 &&  $ownership == 1) )  
          <div class="btn-group navbar-btn">
            <a class="btn btn-primary btn-accept"><i class="icon-thumbs-up3"></i> <span class="hidden-xs position-right">Accept</span></a>
          </div>
          @endif
          @if( $case_info[0]->status == 2 && ($ownership == 2 || $ownership == 5 ) )
          <div class="btn-group navbar-btn">
            <a class="btn btn-default btn-forward"><i class="icon-forward"></i> <span class="hidden-xs position-right">Forward</span></a>
            <a class="btn btn-default btn-close"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Close</span></a>
          </div>
          @endif
          @if( $case_info[0]->status == 3 && ($ownership == 2 || $ownership == 5 ) )
          <div class="btn-group navbar-btn">
            <a class="btn btn-default btn-reopen"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Re-Open</span></a>
          </div>
          @endif
          @if( $case_info[0]->status == 2 && ($ownership == 4 ) ) 
          <div class="btn-group navbar-btn">
            @foreach($participants as $row)
              @if( $row->ownership == 2 )
               <a class="btn"> Assigned to :  {{ ucwords($row->fname.' '.$row->lname) }}</a>
              @endif
            @endforeach
          </div>
          @endif
        @endif
        <div class="pull-right-lg">
          <p class="navbar-text">
            {{  date_format($case_info[0]->created_at,"M d,Y  h:i a") }}
          </p>
          <div class="btn-group navbar-btn">
            <a class="btn btn-default btn-pdf"><i class="icon-printer"></i> <span class="hidden-xs position-right">PDF</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div style="height: 600px !important; overflow-y: scroll;">

  <table class="table"> 
    <tr class="active"><td colspan="2">Call Information</td></tr>
    <tr><td>Call Type:</td><td>{{ $case_info[0]->call_type.'; '.$case_info[0]->subcall_type }}</td></tr>
    @if( !empty($case_info[0]->hospital_related) )
    <tr><td>Hospital Related:</td><td>{{ $case_info[0]->hospital_related }}</td></tr>
    @endif
    @if( !empty($case_info[0]->actions_taken) )
    <tr><td>Action:</td><td>{{ $case_info[0]->actions_taken }}</td></tr>
    @endif

    <tr class="active"><td colspan="2">Caller Information</td></tr>
    @if( !empty($case_info[0]->caller_first_name) )
    <tr><td>First Name:</td><td>{{ $case_info[0]->caller_first_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->caller_last_name) )
    <tr><td>Last Name:</td><td>{{ $case_info[0]->caller_last_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->caller_email_address) )
    <tr><td>Email:</td><td>{{ $case_info[0]->caller_email_address }}<</td></tr>
    @endif
    @if( !empty($case_info[0]->caller_type) )
    <tr><td>Caller Type:</td><td>{{ $case_info[0]->caller_type }}</td></tr>
    @endif
    @if( !empty($case_info[0]->number_of_calls) )
    <tr><td>Number of Calls:</td><td>{{ $case_info[0]->number_of_calls }}</td></tr>
    @endif
    @if( !empty($case_info[0]->patient_telephone_number_confirmation) )
    <tr><td>Provided telephone number:</td><td>{{ $case_info[0]->patient_telephone_number_confirmation }}</td></tr>
    @endif
    @if( !empty($case_info[0]->caller_telephone_number) )
    <tr><td>Telephone Number:</td><td>{{ $case_info[0]->caller_telephone_number }}</td></tr>
    @endif
    

    <tr class="active"><td colspan="2">Patient</td></tr>
    @if( !empty($case_info[0]->patient_first_name) )
    <tr><td>First Name:</td><td>{{ $case_info[0]->patient_first_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->patient_last_name) )
    <tr><td>Last Name:</td><td>{{ $case_info[0]->patient_last_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->patient_telephone_number_confirmation) )
    <tr><td>Provided telephone number:</td><td>{{ $case_info[0]->patient_telephone_number_confirmation }}</td></tr>
    @endif
    @if( !empty($case_info[0]->patient_telephone_number) )
    <tr><td>Telephone Number:</td><td>{{ $case_info[0]->patient_telephone_number }}</td></tr>
    @endif

    <tr class="active"><td colspan="2">Field Worker:</td></tr>
    @if( !empty($case_info[0]->employee_first_name) )
    <tr><td>First Name:</td><td>{{ $case_info[0]->employee_first_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->employee_last_name) )
    <tr><td>Last Name:</td><td>{{ $case_info[0]->employee_last_name }}</td></tr>
    @endif
    @if( !empty($case_info[0]->caregiver_type) )
    <tr><td>Speciality/Title:</td><td>{{ $case_info[0]->caregiver_type }}</td></tr>
    @endif
    @if( !empty($case_info[0]->employee_date_time_of_shift_start) )
    <tr><td>Shift Start:</td><td>{{ $case_info[0]->employee_date_time_of_shift_start }}</td></tr>
    @endif
    @if( !empty($case_info[0]->employee_date_time_of_shift_end) )
    <tr><td>Shift End:</td><td>{{ $case_info[0]->employee_date_time_of_shift_end }}</td></tr>
    @endif

    <tr class="active"><td colspan="2">Other Information</td></tr>
    @if( !empty($case_info[0]->full_message) )
    <tr><td>Full Message:</td><td>{{ $case_info[0]->full_message }}</td></tr>
    @endif
    @if( !empty($case_info[0]->call_language) )
    <tr><td>Call Language:</td><td>{{ $case_info[0]->call_language }}</td></tr>
    @endif

    <tr class="active"><td colspan="2">Account</td></tr>
    <tr><td>Company Information:</td><td>Account Name</td></tr>
    @if( !empty($case_info[0]->team) )
    <tr><td>Team:</td><td>{{ $case_info[0]->team }}</td></tr>
    @endif
    @if( !empty($case_info[0]->call_handled_by_initials) )
    <tr><td>Call Handled By (Initials:</td><td>{{ $case_info[0]->call_handled_by_initials }}</td></tr>
    @endif

    <tr class="active"><td colspan="2">Case Created</td></tr> 
    @if( !empty($case_info[0]->created_on) )
    <tr><td>Date/Time:</td><td>{{ $case_info[0]->created_on }}</td></tr>
    @endif
    @if( !empty($case_info[0]->created_at) )
    <tr><td>Case Sent Date/Time:</td><td>{{ $case_info[0]->created_at }}</td></tr>
    @endif

  </table>
  </div>
</div>


<script type="text/javascript">
  $(".btn-accept").click(function(){
    var case_state = 0;
    $.ajax({
      type: "POST",
      url: "{{ url('check-case') }}",
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}'
      },
      success: function (data) {
        var res = $.parseJSON(data);
        if( res.status == 1 ){
          swal({
            title: "Notice!",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "warning"
          });  
          case_state = 1;
          count_case();
          fetchCase();
        }else{
          swal({
            title: "Are you sure you want to accept this case?",
            // text: "The case will be  .",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm){
            if (isConfirm) {
              $.ajax({
                type: "POST",
                url: "{{ url('accept-case') }}",
                data: { 
                  _token : '{{ csrf_token() }}',
                  case_id : '{{ $case_id }}'
                },
                success: function (data) {
                  var res = $.parseJSON(data);
                  if( res.status == 1 ){
                    swal({
                      title: "Good job!",
                      text: res.message,
                      confirmButtonColor: "#66BB6A",
                      type: "success"
                    });  
                    count_case();
                    fetchCase();
                    dt.search('').draw();
                  }else if( res.status == 2 ){
                    swal({
                      title: "Notice!",
                      text: res.message,
                      confirmButtonColor: "#EF5350",
                      type: "warning"
                    }); 
                    count_case();
                    fetchCase();
                    dt.search('').draw();
                  }else{
                    swal({
                      title: "Oops..!",
                      text: res.message,
                      confirmButtonColor: "#EF5350",
                      type: "error"
                    }); 
                  }
                },
                error: function (data){
                  console.log(data);
                  alert(data.responseJSON.message);
                }
              });
            }
            else {
              swal({
                title: "Cancelled",
                // text: "Transaction cancelled.",
                confirmButtonColor: "#2196F3",
                type: "error"
              });
            }
          });
        }
      },
      error: function (data){
        console.log(data);
        alert(data.responseJSON.message);
      }
    });

  });

   $(".btn-decline").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('decline-case-md') }}", 
      data: {  
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}' 
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        $(".content-data-case").html( data );
        $("#modal-case").modal('show');
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

  $(".btn-forward").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('forward-case-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}' 
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        $(".content-data-case").html( data );
        $("#modal-case").modal('show');
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

  $(".btn-close").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('close-case-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}'
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        $(".content-data-case").html( data );
        $("#modal-case").modal('show');
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

  $(".btn-reopen").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('reopen-case-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}'
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        $(".content-data-case").html( data );
        $("#modal-case").modal('show');
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

  $(".btn-pdf").click(function(){
    window.open("{{ url('pdf-case/'.$case_id) }}", '_blank'); 
  }); 

  $(".btn-pull").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('account/pull-case') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}'
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        var res = $.parseJSON(data);
        if( res.status == 1 ){
          swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success"
          });  
          dt_participants.search('').draw();
          dt.search('').draw();
          count_case();
          fetchCase();
        }else if( res.status == 2 ){
          swal({
            title: "Notice!",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "warning"
          }); 
          dt_participants.search('').draw();
          dt.search('').draw();
          count_case();
          fetchCase();
        }else{
          swal({
            title: "Oops..!",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "error"
          }); 
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
  });

  $(".btn-reviewed").click(function(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('review-case') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}'
      },
      beforeSend: function(){
          $('body').addClass('wait-pointer');
        },
        complete: function(){
          $('body').removeClass('wait-pointer');
        },
      success: function (data) {  
        var res = $.parseJSON(data);
        if( res.status == 1 ){
          swal({
            title: "Good job!",
            text: res.message,
            confirmButtonColor: "#66BB6A",
            type: "success"
          });  
          count_case();
          fetchCase();
        }else if( res.status == 2 ){
          swal({
            title: "Notice!",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "warning"
          }); 
          count_case();
          fetchCase();
        }else{
          swal({
            title: "Oops..!",
            text: res.message,
            confirmButtonColor: "#EF5350",
            type: "error"
          }); 
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
  });

</script>