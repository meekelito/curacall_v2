@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
             <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Archived</span> - Messages</h4>
        </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
                <li class="active">Archived Messages</li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
    <div class="content">
        <div class="panel panel-flat">
            <div class="panel-body">
              <table class="table tbl-messages" cellspacing="0" width="100%">
			          <thead>
			            <tr>
			             	<th>ID</th><th>Message</th><th width="150">Actions</th>
			            </tr>
			          </thead>
			          <tbody>
			          </tbody>
			           <tfoot>
			            <tr>
			              <th>ID</th><th>Message</th><th width="150">Actions</th>
			            </tr>
			          </tfoot>
			        </table>
            </div>
        </div>
    </div>

    <div id="modal-add" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content content-data">

            </div>
        </div>
    </div>

    <div id="modal-update" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content content-data-update">

            </div>
        </div>
    </div>

@endsection  

@section('script')
<script type="text/javascript">
	var dt_messages;
  $(document).ready(function () {
	 	$(".menu-curacall li").removeClass("active");
		$(".menu-archive-messages").addClass('active');

    dt_messages = $('.tbl-messages').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: "{{ url('archived-messages/all') }}",
      columns: [
        {data: 'id'},
        {data: 'message'},
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 
	});
</script>
@endsection 
