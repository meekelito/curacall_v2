@extends('layouts.app')

@section('content')
	<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Contacts</span> - List</h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li><a href="#l"><i class="icon-home2 position-left"></i> Dashboard</a></li>
      <li class="active">Contacts List</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="panel panel-flat">
    <div class="panel-heading">
      <h5 class="panel-title">Contacts</h5>
      <div class="heading-elements">
        <ul class="icons-list">
          <li><a data-action="collapse"></a></li>
          <li><a data-action="reload"></a></li>
        </ul>
      </div>
    </div>
    
    <div class="panel-body">
     
      <table class="table tbl-user" cellspacing="0" width="100%" >
        <thead>
          <tr>
            <th width="20"></th><th>First name</th><th>Last name</th><th>Role</th><th>Email</th><th>Phone Number</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
         <tfoot>
          <tr>
            <th width="20"></th><th>First name</th><th>Last name</th><th>Role</th><th>Email</th><th>Phone Number</th><th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
    
  </div>
</div>

@endsection  

@section('script')
<script type="text/javascript">
  $(".menu-curacall li").removeClass("active");
  $(".menu-contacts").addClass('active');

  $(document).ready(function(){
    dt_messages = $('.tbl-user').DataTable({
      responsive: true,
      processing: true,
      serverSide: true, 
      "aaSorting": [], 
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('contacts/fetch-contacts') }}",
      columns: [
        {data: 'pic', orderable: false, searchable: false},
        {data: 'fname',name: 'users.fname'},
        {data: 'lname',name: 'users.lname'},
        {data: 'role_title',name: 'b.role_title'},
        {data: 'email',name: 'users.email'},
        {data: 'phone_no',name: 'users.phone_no'},
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 
  }); 
</script>
@endsection 

