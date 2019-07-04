@extends('layouts.app')
@section('css')
<style>
  .show{
    display: block;
  }
     .select2-results__option[aria-selected=true] {
      display: none;
    }
</style>
@endsection
@section('content')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Conversation</span></h4>
        </div>

    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ url('/') }}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{ url('new-message') }}">Messages</a></li>
            <li class="active">Conversation</li>
        </ul>
    </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="row">
  <div class="col-lg-8">
    <div class="panel panel-flat">
      <div class="panel-body" id="message-container"> 
          <input type="hidden" id="room" value="{{ $room_id }}">
          <input type="hidden" id="currentpage" value="1" />
          <input type="hidden" id="lastpage" value="" />

          <div style="min-height: 400px;">
          <chat-messages :messages="messages" :user="{{ Auth::user()->id }}" :room_id="{{ $room_id }}" ref="chatmessages"></chat-messages>
          </div>
          <div style="height: 20px;">
          <span  class="help-block" v-bind:class="[ typing && room == {{$room_id}} ? 'show': '']" style="font-style: italic;display:none">
              @{{ user.fname }} is typing...
          </span> 
          </div>
          <chat-form v-on:messagesent="addMessage" :user="{{ Auth::user() }}" :room_id="{{ $room_id }}"></chat-form>
      </div>
    </div>
  </div>
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
            <div class="media-left"><img src="{{ asset('storage/uploads/users/'.$row->prof_img.'?v='.strtotime('now')) }}" class="img-circle img-md" alt=""></div>
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

      <a id="btnAddParticipant" href="javascript:showAddParticipant()" class="btn btn-xs btn-default" style="margin:15px"><i class="icon-user-plus"></i> Add Participant/s</a>

       <div id="divNewParticipant" style="display:none" class="panel-body">
        <div class="row col-sm-12">
            <form class="form-horizontal" id="form-message"  method="POST" action="{{ route('add-chat-participant') }}">
             {{ csrf_field() }}
             <input type="hidden" name="room_id" value="{{ $room_id }}" />
            <div class="form-group">
              <div class="col-sm-12">
                <select name="recipients[]"  multiple="multiple" class="select2" style="width:100%">
                  @foreach($users as $row)
                    <option value="{{ $row->id }}">{{ $row->fname.' '.$row->lname }}</option>
                  @endforeach
                </select>
              </div>
             
            </div>
            
            <div class="form-group">
             <div class="col-sm-12">
                <button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right btn-new-message"><b><i class="icon-user-plus"></i></b> Add</button>
                <button onclick="cancelAddParticipant()" type="button" class="btn btn-danger">Cancel</button>
              </div>
            </div>
          </form>
        </div>
        </div>
    </div>
    <!-- /collapsible list -->


  </div>
  </div>
</div>
@endsection
@section('script')
<script> 
  $(".menu-curacall li").removeClass("active");
  $(".menu-messages").addClass('active');
  $(".submenu-curacall li").removeClass("active");

  $.getScripts({
    urls: ["{{ asset('js/app.js') }}"],
    cache: true,  // Default
    async: false, // Default
    success: function(response) {
            $('.select2').select2();

    }
  });

  function showAddParticipant()
  {
     $("#divNewParticipant").show();
     $('#btnAddParticipant').hide();
     $('.select2').focus();
  }

  function cancelAddParticipant()
  {
     $("#divNewParticipant").hide();
     $('#btnAddParticipant').show();
  }
</script>



@endsection