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
<div class="content" id="content-case">
  
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
    fetchCase();
  }); 

  function fetchCase(){
    $.ajax({ 
      type: "POST", 
      url: "{{ url('fetch-case') }}", 
      data: {  
        _token : '{{ csrf_token() }}',
        case_id : '{{ $case_id }}' 
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

</script>

@endsection 
