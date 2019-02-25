<div class="modal-header bg-primary">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Forward Case [#0001]</h4>
</div>
<form class="form-horizontal" id="form-status-update">
	{{ csrf_field() }}
	<input type="hidden" name="_id" value="">
	<div class="modal-body">
    <fieldset class="content-group">
      <div class="form-group">
        <label class="control-label col-lg-3 text-right">Remarks/Note :</label>
        <div class="col-lg-9">
          <textarea class="form-control" rows="3" required></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3 text-right">Forward to :</label>
        <div class="col-lg-9">
          <select name="recipient[]" multiple="multiple" class="select" style="width: 100%;" required>
            @foreach($users as $row)
              <option value="{{ $row->id }}">{{ $row->fname.' '.$row->lname }}</option>
            @endforeach 
          </select>
        </div>
      </div>
    </fieldset>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Send</button>
	</div>
</form>

<script type="text/javascript">
  $('.select').select2();
</script>


