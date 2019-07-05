@extends('layouts.app')

@section('css')
  <style type="text/css">
	.message-board-list{
		cursor: pointer;
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
      <li class="active">Message Board</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
	<div class="form-group">
		<button class="btn btn-primary"><i class="icon-plus3"></i> New Message</button>
	</div>
	<!-- Cards layout -->
	<ul class="media-list content-group">
		<?php use Illuminate\Support\Str; ?>
		@foreach($messages as $row)
		<li class="message-board-list media panel panel-body stack-media-on-mobile" onclick="openMessage('{{ $row->id }}')">
			<div class="media-left">
				<a href="{{ route('message-board.show',$row->id) }}">
					<img src="{{ asset('storage/uploads/users/'.$row->user->prof_img) }}" class="img-rounded img-lg" alt="">
				</a>
			</div>

			<div class="media-body">
				<h6 class="media-heading text-semibold">
					<a href="{{ route('message-board.show',$row->id) }}">{{ $row->title }}</a>
				</h6>

				<ul class="list-inline list-inline-separate text-muted mb-10">
					<li><a href="{{ route('message-board.show',$row->id) }}" class="text-muted">{{ $row->user->fname . ' ' . $row->user->lname }}</a></li>
					<li>{{ $row->user->role->role_title }}</li>
				</ul>

				{{ Str::limit($row->content,300) }}
			</div>

			<div class="media-right text-nowrap">
				<span class="text-muted">{{ $row->created_at->diffForHumans() }}</span>
			</div>
		</li>
		@endforeach
	</ul>
	<!-- /cards layout -->


	<!-- Pagination -->
	<div class="text-center content-group-lg pt-20">
		<ul class="pagination">
			<li class="disabled"><a href="#"><i class="icon-arrow-small-left"></i></a></li>
			<li class="active"><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li><a href="#">5</a></li>
			<li><a href="#"><i class="icon-arrow-small-right"></i></a></li>
		</ul>
	</div>
	<!-- /pagination -->

					
</div>


@endsection  

@section('script')
<script type="text/javascript">
  $(document).ready(function () {
      $(".menu-curacall li").removeClass("active");
	  $(".menu-messageboard").addClass('active');
  }); 

  function openMessage(id)
  {
  	$.pjax.reload('#content',{ url: "{{ url('message-board') }}/"+id });
  }
</script>
@endsection 
