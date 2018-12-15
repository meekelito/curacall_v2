@extends('layouts.app')
@section('css')
  <style type="text/css">
    .panel-toolbar-inbox {
      background-color: #fff;
    }
    .select2-results__option[aria-selected=true] {
      display: none;
    }
  </style>
@endsection 
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Message</span> - New</h4>
    </div>
    <div class="heading-elements">
      <form class="heading-form" action="#">
        <div class="form-group">
        </div>
      </form>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li><a href="#l"><i class="icon-home2 position-left"></i> Home</a></li>
      <li><a href="#">Message</a></li>
      <li class="active">New</li>
    </ul>
  </div>
</div>
<!-- /page header -->

<div class="col-lg-12">
  <div class="panel panel-flat">
    <div class="panel-body">
      <form class="form-horizontal" id="form-message"  method="POST" action="{{ url('create-room') }}">
         {{ csrf_field() }}
        <div class="form-group">
          <label class="col-sm-1 control-label">To :</label>
          <div class="col-sm-9">
            <select name="recipient[]" multiple="multiple" class="select" style="width: 100%;" required>
              @foreach($users as $row)
                <option value="{{ $row->id }}">{{ $row->fname.' '.$row->lname }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right btn-new-message"><b><i class="icon-circle-right2"></i></b> Message</button>
          </div>
        </div>
      </form>
        <div class="col-lg-12 text-center" style="margin-bottom: 20px;">
          <span class="text-semibold text-size-large">Recent Messages</span>
        </div>

        <div class="form-group">
          <div class="col-sm-12">
            @if(!$participants->isEmpty())
            @php
              $room = $participants[0]->room_id;
              $count=1;
            @endphp

            @foreach($participants as $row)

              @switch($row->participants_no)
                @case(2)
                    <div class="text-center" style="float:left; height: 120px; width: 120px;">
                    <a href="{{ url('/messages/room',$row->room_id) }}">
                      <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" width="80" class="img-circle" title="{{ $row->fname.' '.$row->lname }}">
                    </a>
                    <div style="padding: 5px;">
                      <label>{{ $row->fname }} </label>
                    </div>
                    </div>
                    @break
                @case(3)
                    @php($count++)
                    @if($count == 2)
                    <div class="text-center" style="float:left; height: 120px; width: 120px;">
                    <div style="height: 82px; border: 1px solid #fff;">
                    <a href="{{ url('/messages/room',$row->room_id) }}" style="margin-left: 10px; float: left;">
                    @else
                    <a href="{{ url('/messages/room',$row->room_id) }}" style="position: relative; left:20px;top: -30px;">
                    @endif
                      <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" width="55" class="img-circle" title="{{ $row->fname.' '.$row->lname }}">
                    </a>
                    @if($count == $row->participants_no)
                    </div>
                    <div style="padding: 5px; width: 120px; text-align: center;">
                      <label>{{ $row->fname.' + '.($row->participants_no-2) }} </label>
                    </div>
                     @endif
                    @if($count == $row->participants_no)
                      </div>
                      @php($count=1)
                    @endif
                    @break
                @case(4)
                    @php($count++)
                    @if($count == 2)
                    <div class="text-center" style="float:left; height: 120px; width: 120px;">
                      
                    @endif
                    <a href="{{ url('/messages/room',$row->room_id) }}">
                     <div style="display: table-cell; padding: 0 5px;">
                        <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" width="40" class="img-circle" title="{{ $row->fname.' '.$row->lname }}">
                      </div>
                    </a>
                    @if($count == $row->participants_no)
                    
                    <div style="padding: 5px;">
                      <label>{{ $row->fname.' + '.($row->participants_no-2) }} </label>
                    </div>
                     @endif
                    @if($count == $row->participants_no)
                      </div>
                      @php($count=1)
                    @endif
                    @break
                  @case(5)
                    @php($count++)
                    @if($count == 2)
                    <div class="text-center" style="float:left; height: 120px; width: 120px;">
                      
                    @endif
                    <a href="{{ url('/messages/room',$row->room_id) }}">
                     <div style="display: table-cell; padding: 0 5px;">
                        <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" width="40" class="img-circle" title="{{ $row->fname.' '.$row->lname }}">
                      </div>
                    </a>
                    @if($count == $row->participants_no)
                    
                    <div style="padding: 5px;">
                      <label>{{ $row->fname.' + '.($row->participants_no-2) }} </label>
                    </div>
                     @endif
                    @if($count == $row->participants_no)
                      </div>
                      @php($count=1)
                    @endif
                    @break
                @case($row->participants_no > 5)
                  @php($count++)
                  @if($count <= 5)
                    @if($count == 2)
                    <div class="text-center" style="float:left; height: 120px; width: 120px;">
                    @endif
                    <a href="{{ url('/messages/room',$row->room_id) }}">
                     <div style="display: table-cell; padding: 0 5px;">
                        <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" width="40" class="img-circle" title="{{ $row->fname.' '.$row->lname }}">
                      </div>
                    </a>
                    @if($count == 5)
                    <div style="padding: 5px;">
                      <label>{{ $row->fname.' + '.($row->participants_no-2) }} </label>
                    </div>
                     @endif
                    @if($count == 5)
                      </div>
                      
                    @endif
                    @if($count == $row->participants_no)
                    @php($count=1)
                    @endif
                    
                  @else
                    @if($count == $row->participants_no)
                    @php($count=1)
                    @endif

                  @endif
                @break
                @default
                    default
                    
              @endswitch

            @endforeach
            @endif
          </div>
        </div>

        <div class="row" style="padding: 10px;">
          <div class="col-sm-6">

          </div>
          
        </div>
      
    </div>
  </div>
</div>

@endsection  
 
@section('script')

<script type="text/javascript">
  $(".menu-curacall li").removeClass("active");
  $(".menu-messages").addClass('active');
  $(".menu-messages-new").addClass('active');
  $(".menu-messages .hidden-ul").css("display", "block");

  $('.select').select2();
  
  // $( "#form-message" ).submit(function( e ) {
  //   $.ajax({  
  //     type: "POST",
  //     url: "{{ url('create-room') }}",
  //     data: $('#form-message').serialize(),
  //     success: function (data) {
  //       // var res = $.parseJSON(data);
  //       alert(data);
        
  //     },
  //     error: function (data) {
  //       swal({
  //         title: "Oops...",
  //         text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.!",
  //         confirmButtonColor: "#EF5350",
  //         type: "error"
  //       });
  //     },
  //   }); 
  //   e.preventDefault();
  // });

</script>
<script  src="{{ asset('js/app.js') }}" type="text/javascript" defer></script> 
@endsection 