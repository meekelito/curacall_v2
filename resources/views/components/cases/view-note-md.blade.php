<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Note</h4>
</div>
<div class="modal-body">
  
    <div class="media-left">
      <img src="{{ asset('storage/uploads/users/'.$note[0]->prof_img) }}" class="img-circle img-xs" alt="">
    </div>

    <div class="media-body">
      <a>
        {{$note[0]->fname.' '.$note[0]->lname}}
        <span class="media-annotation pull-right">
          @if(!empty($note[0]->created_at))
            @if( date('Y-m-d') == date('Y-m-d', strtotime($note[0]->created_at)))
                {{  date_format($note[0]->created_at,"h:i a") }}
            @else
                {{  date_format($note[0]->created_at,"M d") }}
            @endif
          @endif
        </span>
      </a>

      <span class="display-block text-muted">{{$note[0]->note}}</span>
    </div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
</div>





