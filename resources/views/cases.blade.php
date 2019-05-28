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
    <div class="col-lg-8" id="content-case" style="min-height: 400px;">
    </div>
    <div class="col-lg-4">
      <div class="panel panel-flat">
        <div class="panel-toolbar panel-toolbar-inbox">
          <div class="navbar navbar-default">
            <div class="navbar-collapse collapse">
              <div class="pull-left-lg">
                <p class="navbar-text text-size-large text-semibold">
                  Notes
                </p>
              </div>
              <div class="pull-right-lg">
                <div class="btn-group navbar-btn">
                  <button type="button" class="btn btn-primary btn-icon btn-rounded btn-sm btn-add-note" title="Add note(s)"><i class="icon-plus3"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table class="table table-borderless" id="tbl-notes"  width="100%">
          <thead >
            <tr>
              <th style="padding: 0 !important; "></th>
            </tr>
          </thead> 
          <tbody>
          </tbody>
        </table>  
      </div>
    </div>


    <div class="col-lg-4">
      <div class="panel panel-flat">
        <div class="panel-toolbar panel-toolbar-inbox">
          <div class="navbar navbar-default">
            <div class="navbar-collapse collapse">
              <div class="pull-left-lg">
                <p class="navbar-text text-size-large text-semibold">
                  Participants
                </p>
              </div>

            </div>
          </div>
        </div>
        <table class="table table-borderless" id="tbl-participants"  width="100%">
          <thead >
            <tr>
              <th style="padding: 0 !important;"></th>
            </tr>
          </thead> 
          <tbody>
            <tr><td class="text-center">No data available in table</td></tr>
          </tbody>
        </table>  
      </div>
    </div>
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
  var dt,dt_participants;
  $(document).ready(function () {
    // $(".menu-curacall li").removeClass("active");
    // $(".menu-cases").addClass('active'); 
    fetchCase();
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

    dt_participants = $('#tbl-participants').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      searching: false, 
      paging: false,
      bInfo: false,
      ordering: false,
      ajax: "{{ url('case/participants/'.$case_id) }}",
        columns: [
          {data: 'participants', orderable: false, searchable: false}
        ] 
    });
  }); 

  function fetchCase(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('fetch-case') }}", 
      data: {  
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}',
        is_reviewed : '{{ $is_reviewed }}' 
      },
      success: function (data) {  
        $("#content-case").html( data );
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
</script>

@endsection 
