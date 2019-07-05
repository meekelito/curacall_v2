@extends('layouts.app')

@section('css')
  <style type="text/css">
	.message-board-list{
		cursor: pointer;
	}
	.panel-message-board{
		font-size: 15px;
	}
	.comments .media{
		background-color:#FAF8F7;
		border-radius: 10px;
		padding: 20px;
	}
  </style>
@endsection 

@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Message Board</span></h4>
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
      <li><a href="{{ url('/') }}"><i class="icon-home2 position-left"></i> Home</a></li>
       <li><a href="{{ route('message-board.index') }}"> Message Board</a></li>
      <li class="active">{{ $message->title }}</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
	<!-- Post -->
	<div class="panel-message-board panel col-md-8">
		<div class="panel-body">
			<div class="content-group-lg">
				<div class="form-group">
					<a href="{{ route('message-board.index') }}" class="btn btn-default">Back</a>
				</div>
				<h3 class="text-semibold mb-5">
					<a href="#" class="text-default">{{ $message->title }}</a>
				</h3>

				<ul class="list-inline list-inline-separate text-muted content-group">
					<li>By <a href="#" class="text-muted">{{ $message->user->fname . ' ' . $message->user->lname }} - {{ $message->user->role->role_title }}</a></li>
					<li>July 5th, 2016</li>
					<li><a href="#" class="text-muted">12 comments</a></li>
				</ul>

				<div class="content-group">
					{{ $message->content }}
				</div>

			</div>

			<hr>
			
			<div class="">
				<div class="panel-heading">
					<h4 class="panel-title text-semiold">Comments</h4>
					<div class="heading-elements">
						<ul class="list-inline list-inline-separate heading-text text-muted">
							<li>42 comments</li>
						</ul>
                	</div>
				</div>

				<div class="panel-body">
					<ul class="comments media-list stack-media-on-mobile">
						<li class="media">
							<div class="media-left">
								<a href="#"><img src="{{ asset('storage/uploads/users/'.$message->user->prof_img) }}" class="img-circle img-sm" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-heading">
									<a href="#" class="text-semibold">William Jennings</a>
									<span class="media-annotation dotted">Just now</span>
								</div>

								<p>He moonlight difficult engrossed an it sportsmen. Interested has all devonshire difficulty gay assistance joy. Unaffected at ye of compliment alteration to.</p>


							</div>
						</li>
						
						<li class="media">
							<div class="media-left">
								<a href="#"><img src="{{ asset('storage/uploads/users/'.$message->user->prof_img) }}" class="img-circle img-sm" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-heading">
									<a href="#" class="text-semibold">Margo Baker</a>
									<span class="media-annotation dotted">5 minutes ago</span>
								</div>

								<p>Place voice no arise along to. Parlors waiting so against me no. Wishing calling are warrant settled was luckily. Express besides it present if at an opinion visitor.</p>

							</div>
						</li>

					

						<li class="media">
							<div class="media-left">
								<a href="#"><img src="{{ asset('storage/uploads/users/'.$message->user->prof_img) }}" class="img-circle img-sm" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-heading">
									<a href="#" class="text-semibold">Victoria Johnson</a>
									<span class="media-annotation dotted">3 hours ago</span>
								</div>

								<p>Finished why bringing but sir bachelor unpacked any thoughts. Unpleasing unsatiable particular inquietude did nor sir.</p>


							</div>
						</li>
					</ul>
				</div>

				<hr class="no-margin">

				<div class="panel-body">
					<h6 class="no-margin-top content-group">Add comment</h6>
					<div class="content-group">
						<div id="add-comment">Get his declared appetite distance his together now families. Friends am himself at on norland it viewing. Suspected elsewhere you belonging continued commanded she...</div>
					</div>
					
					<div class="text-right">
						<button type="button" class="btn bg-blue"><i class="icon-plus22"></i> Add comment</button>
					</div>
				</div>
			</div>
			<!-- /comments -->


		</div>
	</div>
	

	<div class="col-md-4">
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
             <input type="hidden" name="room_id" value="" />
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
@endsection  

@section('script')
<script type="text/javascript">
  $(document).ready(function () {
      $(".menu-curacall li").removeClass("active");
	  $(".menu-messageboard").addClass('active');
  }); 

</script>
@endsection 
