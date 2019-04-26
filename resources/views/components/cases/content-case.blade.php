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
        @if( ($case_info[0]->status == 1 && $participation[0]->ownership == 1) || ($case_info[0]->status == 2 &&  $participation[0]->ownership == 1) )  
        <div class="btn-group navbar-btn">
          <a class="btn btn-primary btn-accept"><i class="icon-thumbs-up3"></i> <span class="hidden-xs position-right">Accept</span></a>
          <!-- <a class="btn btn-warning btn-decline"><i class="icon-thumbs-down3"></i> <span class="hidden-xs position-right">Decline</span></a> -->
        </div>
        @endif
        @if( $case_info[0]->status == 2 && ($participation[0]->ownership == 2 || $participation[0]->ownership == 5 ) )
        <div class="btn-group navbar-btn">
          <a class="btn btn-default btn-forward"><i class="icon-forward"></i> <span class="hidden-xs position-right">Forward</span></a>
          <a class="btn btn-default btn-close"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Close</span></a>
        </div>
        @endif
        @if( $case_info[0]->status == 3 && ($participation[0]->ownership == 2 || $participation[0]->ownership == 5 ) )
        <div class="btn-group navbar-btn">
          <a class="btn btn-default btn-reopen"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Re-Open</span></a>
        </div>
        @endif
        @if( $case_info[0]->status == 2 && ($participation[0]->ownership == 3 || $participation[0]->ownership == 4 ) ) 
        <div class="btn-group navbar-btn">
          @foreach($participants as $row)
            @if( $row->ownership == 2 )
             <a class="btn"> Assigned to :  {{ ucwords($row->fname.' '.$row->lname) }}</a>
            @endif
          @endforeach
        </div>
        @endif
        
        <div class="pull-right-lg">
          <p class="navbar-text">

            {{  date_format($case_info[0]->created_at,"M d,Y  h:i a") }}
           
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
                  alert("No connection could be made because the target machine actively refused it. Please refresh the browser and try again.");
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
        alert("No connection could be made because the target machine actively refused it. Please refresh the browser and try again.");
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

</script>