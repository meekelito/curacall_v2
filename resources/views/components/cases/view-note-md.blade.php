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
          {{date_format($note[0]->created_at,"M d,Y  h:i a")}}
        </span>
      </a>

      <span class="display-block text-muted">
        @if($note[0]->note==null)
          {{$note[0]->action_note}}
        @else
          {{$note[0]->note}}
        @endif
      </span>
    </div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
</div>





