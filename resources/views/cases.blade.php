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
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Case</span></h4>
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
      <li><a href="#">Case</a></li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
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
                  <a class="btn btn-default btn-accept"><i class="icon-thumbs-up2"></i> <span class="hidden-xs position-right">Accept</span></a>
                  <a class="btn btn-default btn-decline"><i class="icon-thumbs-down2"></i> <span class="hidden-xs position-right">Decline</span></a>
                </div>
                <div id="case_open" @if( $case_info[0]->status == 2 && $participation[0]->ownership == 3 ) class="btn-group show" @else class="btn-group hidden" @endif>
                  <a class="btn btn-default btn-forward"><i class="icon-forward"></i> <span class="hidden-xs position-right">Forward</span></a>
                  <a class="btn btn-default btn-close"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Close</span></a>
                </div>
                <div id="case_closed" @if( $case_info[0]->status == 3 ) class="btn-group show" @else class="btn-group hidden" @endif>
                  <a class="btn btn-default btn-reopen"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Re-Open</span></a>
                </div>

              </div>
              <div class="pull-right-lg">
                <p class="navbar-text">
                  @if(!empty($case_info[0]->created_at ))
                    @if( date('Y-m-d') == date('Y-m-d', strtotime($case_info[0]->created_at)))
                        {{  date_format($case_info[0]->created_at,"h:i a") }}
                    @else
                        {{  date_format($case_info[0]->created_at,"M d") }}
                    @endif
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
</div>

<div id="modal-case" class="modal fade" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content content-data-case">

    </div>
  </div>
</div>

<div id="modal-note" class="modal fade" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content content-data-note">

    </div>
  </div>
</div>
@endsection  

@section('script')
<script type="text/javascript">
  var dt;
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-cases").addClass('active'); 

    dt = $('#tbl-notes').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      searching: false, 
      paging: false,
      bInfo: false,
      ordering: false,
      ajax: "{{ url('case/notes/'.$case_id) }}",
      columns: [
        {data: 'note', orderable: false, searchable: false}
      ] 
    });

  }); 

  $(".btn-accept").click(function(){

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
              $("#case_active").removeClass("show");
              $("#case_active").addClass('hidden'); 

              $("#case_closed").removeClass("show");
              $("#case_closed").addClass('hidden'); 

              $("#case_open").removeClass("hidden");
              $("#case_open").addClass('show'); 

            }else if( res.status == 2 ){
              swal({
                title: "Notice!",
                text: res.message,
                confirmButtonColor: "#EF5350",
                type: "warning"
              }); 
              $("#case_active").removeClass("show");
              $("#case_active").addClass('hidden'); 
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

  $(".btn-add-note").click(function(){
    $.ajax({ 
      type: "POST",  
      url: "{{ url('add-note-md') }}", 
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

  function view_note(id){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('view-note-md') }}", 
      data: { 
        _token : '{{ csrf_token() }}',
        id: id
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
  }

</script>

@endsection 
