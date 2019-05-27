@extends('layouts.app')
@section('css')
  <link href="{{ asset('assets/js/plugins/trees/checktree.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
  <!-- Page header -->
  <div class="page-header page-header-default">
      <div class="page-header-content">
          <div class="page-title">
              <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Admin</span> - Roles</h4>
          </div>
      </div>
      <div class="breadcrumb-line">
          <ul class="breadcrumb">
              <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
              <li class="active">Roles</li>
          </ul>
      </div>
  </div>
  <!-- /page header -->
  <div class="content">
    <div class="panel panel-flat">
      <div class="panel-body">
         <div class="tabbable nav-tabs-vertical nav-tabs-left">
                <ul class="nav nav-tabs nav-tabs-highlight" style="width: 200px;">
                    <li class="active"><a data-toggle="tab" href="#roles-tab"><i class="icon-user-tie position-left"></i> Roles</a></li>
                    <li><a data-toggle="tab" href="#permissions-tab"><i class="icon-user-block position-left"></i> Permissions</a></li>
                    <li><a data-toggle="tab" href="#accounts-tab"><i class="icon-office position-left"></i> Client Roles </a></li>
                    <li><a data-toggle="tab" href="#curacall-tab"><i class="icon-headset position-left"></i> Curacall Roles</a></li>

                </ul>

                <div class="tab-content">
                   <div class="tab-pane active" id="roles-tab">   
                    <div class="form-group">
                        <button onclick="add_role()" class="btn btn-primary btn-xs"><i class="icon-plus3"></i> Add Role</button>
                    </div>     
                    <table class="table tbl-roles" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>Role Title</th><th>Description</th><th>Owner</th><th width="150">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                       <tfoot>
                        <tr>
                            <th>Role Title</th><th>Description</th><th>Owner</th><th width="150">Actions</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <div class="tab-pane" id="permissions-tab">        
                    <table class="table tbl-permissions" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Module</th><th>Name</th><th>Description</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                       <tfoot>
                        <tr>
                          <th>Module</th><th>Name</th><th>Description</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <div class="tab-pane" id="accounts-tab">
                    <form class="form-horizontal">
                      <div class="col-lg-12">
                       
                        <table class="table tbl-client-roles" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Account ID</th><th>Name</th><th width="150">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                           <tfoot>
                            <tr>
                              <th>Account ID</th><th>Name</th><th width="150">Actions</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </form>
                  </div>

                  <div class="tab-pane" id="curacall-tab">   
                    <table class="table tbl-admin-roles" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                       <tfoot>
                        <tr>
                          <th>Role Title</th><th>Description</th><th width="150">Actions</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
 
    <div id="modal-add-role" class="modal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Add Role</h5>
          </div>
          <form id="frmAddRole" class="form-horizontal" method="POST" action="{{ route('admin.roles.create') }}">
            {{ csrf_field() }}
            <div class="modal-body">
              <fieldset class="content-group">
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Role Title :</label>
                  <div class="col-lg-9">
                    <input id="txtRoleName" type="text" class="form-control" name="role_title" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Description :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="description" required>
                  </div>
                </div>  

                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Owner :</label>
                  <div class="col-lg-9">
                   <select name="is_curacall" class="form-control">
                      <option value="0">Client</option>
                      <option value="1">Curacall</option>
                   </select>
                  </div>
                </div> 

                <div class="row well">
                  <h5>Permissions</h5>
                  <ul class="checktree">
                   @foreach($permissions as $key => $permission)
                      <li>
                        <input id="{{ $key }}" type="checkbox" /> <label for="{{ $key }}">{{ $key }}</label>
                        <ul>
                          @foreach($permission as $row)
                           
                          <li><input id="{{ $row['name'] }}" type="checkbox" name="permissions[]" value="{{ $row['name'] }}" /> <label for="{{ $row['name'] }}">{{ $row['description'] }}</label></li>
                          @endforeach
                         
                        </ul>
                      </li>
                      @endforeach
                    </ul>
                </div>
              
              </fieldset>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
      </div>
    </div>
  </div>


  <div id="modal-edit-role" class="modal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Edit Role</h5>
          </div>
          <form id="frmEditRole" class="form-horizontal" method="POST">
            {{ csrf_field() }}
              <input type="hidden" name="_method" value="PUT">
            <div class="modal-body">
              <fieldset class="content-group">
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Role Title :</label>
                  <div class="col-lg-9">
                    <input id="txtEditRoleName" type="text" class="form-control" name="role_title" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Description :</label>
                  <div class="col-lg-9">
                    <input id="txtEditRoleDescription" type="text" class="form-control" name="description" required>
                  </div>
                </div>  

                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Owner :</label>
                  <div class="col-lg-9">
                   <select id="txtEditRoleOwner" name="is_curacall" class="form-control">
                      <option value="0">Client</option>
                      <option value="1">Curacall</option>
                   </select>
                  </div>
                </div> 

                <div class="row well">
                  <h5>Permissions</h5>
                  <ul id="editPermission" class="checktree">
                      @foreach($permissions as $key => $permission)
                      <li>
                        <input id="{{ $key }}" type="checkbox" /> <label for="{{ $key }}">{{ $key }}</label>
                        <ul>
                          @foreach($permission as $row)
                          <li><input id="{{ $row['name'] }}" type="checkbox" name="permissions[]" value="{{ $row['name'] }}" /> <label for="{{ $row['name'] }}">{{ $row['description'] }}</label></li>
                          @endforeach
                        </ul>
                      </li>
                      @endforeach
                    </ul>
                </div>
              
              </fieldset>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
      </div>
    </div>
  </div>

  <div id="modal-edit-account-role" class="modal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content content-edit-role">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Client's Roles</h5>
          </div>
          <form class="form-horizontal" id="frmUpdateAccountRoles" method="POST" action="">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <div class="modal-body">
              <!-- Disable filter -->
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h5 class="panel-title">Assign roles</h5>
                </div>

                <div class="panel-body">
                  <p class="content-group">Click roles from the left to assign it to the account on the right tab</p>
                  <div class="col-md-12"><span class="pull-left">All Roles</span>    <span id="account-role" class="pull-right">Account Roles</span></div>
                  <select multiple="multiple" id="cmbAccountRoles" name="role_ids[]" class="form-control listbox-filter-disabled">
                  </select>
                </div>
              </div>
              <!-- /disable filter -->
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
          </form>
      </div>
    </div>
  </div>
@endsection  

@section('script')
<script type="text/javascript">
  $(function(){
      $.getScripts({
        urls: ["{{ asset('assets/js/plugins/trees/checktree.js') }}",
                "{{ asset('assets/js/plugins/forms/inputs/duallistbox.min.js') }}"],
        cache: true,  // Default
        async: false, // Default
        success: function(response) {
              $("ul.checktree").checktree();
              $('.checktree input').uniform();

             // Disable filtering
              $('.listbox-filter-disabled').bootstrapDualListbox({
                  showFilterInputs: false
              });
        }
      });
  });
  var dt_admin,dt_client,dt_roles,dt_permissions,account_name;

    $(".menu-curacall li").removeClass("active");
    $(".menu-admin-console-roles").addClass('active');

    dt_admin = $('.tbl-admin-roles').DataTable({
      pageLength: 100,
      lengthMenu : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      "order": [],
      ajax: "{{ route('admin.roles.curacall') }}",
      columns: [
        {data: 'name'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 

    dt_client = $('.tbl-client-roles').DataTable({
      pageLength: 100,
      lengthMenu : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      "order": [],
      ajax: "{{ route('admin.clients.fetch') }}",
      columns: [
        {data: 'account_id'},
        {data: 'account_name'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });

    dt_roles = $('.tbl-roles').DataTable({
      pageLength: 100,
      lengthMenu : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ route('admin.roles.fetch') }}",
      "order": [],
      columns: [
        {data: 'role_title'},
        {data: 'description'},
        {
                data: 'is_curacall',
                render: function ( data, type, row ) {
                    if(data == 0)
                      return "<span class='badge badge-danger'>Client</span>";
                    else
                      return "<span class='badge badge-primary'>Curacall</span>";
                }
            },
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 

      dt_permissions = $('.tbl-permissions').DataTable({
      pageLength: 100,
      lengthMenu : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ route('admin.permissions.fetch') }}",
      "order": [],
      columns: [
        {data: 'module'},
        {data: 'name'},
        {data: 'description'}
      ]
    }); 


  function show_edit_account_role_modal(id,name = "test")
  {
      account_name = name;
      $.ajax({
            url: "{{ route('admin.account.roles') }}", 
            type: "GET",             
            data: { account_id : id },      
            beforeSend: function(){
                  $('body').addClass('wait-pointer');
            },
            complete: function(){
              $('body').removeClass('wait-pointer');
            },          
            success: function(data) {
              var result = $.parseJSON(data);
              //console.log(result.update_url);
                   $('#frmUpdateAccountRoles').attr('action',result.update_url);

                    $('#cmbAccountRoles').empty().trigger('change');
                    $.each(result.roles, function(i, item) {
                      $("#cmbAccountRoles").append("<option value='"+item.id+"'>"+item.description+"</option>");
                    });

                    $('#cmbAccountRoles').bootstrapDualListbox('refresh', true);

                    $.each(result.account_roles, function(i, item) {
                      $("#cmbAccountRoles option[value='"+item.id+"']").remove();
                        $("#cmbAccountRoles").append("<option value='"+item.id+"' selected='selected'>"+item.description+"</option>");
                    });

                    $('#cmbAccountRoles').bootstrapDualListbox('refresh', true);
              $('#account-role').html(name+ '\'s Roles');
              $('#modal-edit-account-role').modal('show');
            },
            error: function(data, errorThrown)
            {
                //$(block).unblock()
                // $('#content').unblock();
                // notify('request failed :'+errorThrown,"error");
                //    notify(data.responseJSON.error[Object.keys(data.responseJSON.error)[0]]);
            }
        });
  }



  function show_edit_role_modal(id)
  {
  
      $.ajax({
            url: "{{ route('admin.roles.editrole') }}", 
            type: "GET",             
            data: { role_id : id },      
            beforeSend: function(){
                  $('body').addClass('wait-pointer');
            },
            complete: function(){
              $('body').removeClass('wait-pointer');
            },          
            success: function(data) {

              var obj = $.parseJSON(data);
              //console.log(obj.permissions);

                  $('#frmEditRole').attr('action',obj.update_url);
                  $('#modal-edit-role').modal('show');
                  $('#frmEditRole').trigger("reset");
                  $('#txtEditRoleName').focus();
                  $('#txtEditRoleName').val(obj.role.role_title);
                  $('#txtEditRoleDescription').val(obj.role.description);
                  $('#txtEditRoleOwner').val(obj.role.is_curacall).trigger('change');

                  $.each(obj.permissions, function(i, val){

                     $("#editPermission ul li input[value='" + val + "']").prop('checked', true);
                     $.uniform.update();

                  });

            },
            error: function(data, errorThrown)
            {
                //$(block).unblock()
                // $('#content').unblock();
                // notify('request failed :'+errorThrown,"error");
                //    notify(data.responseJSON.error[Object.keys(data.responseJSON.error)[0]]);
            }
        });

  }

    function add_role()
    {
      $('#modal-add-role').modal('show');
      $('#frmAddRole').trigger("reset");
      $('#txtRoleName').focus();
    }

      $('#frmAddRole').on('submit',function (ev) {
              ev.preventDefault();
               var frm = $(this);
                $.ajax({
                  type: frm.attr('method'),
                  url: frm.attr('action'),
                  data: frm.serialize(),
                  beforeSend: function(){
                    $('body').addClass('wait-pointer');
                  },
                  complete: function(){
                    $('body').removeClass('wait-pointer');
                  },
                  success: function (data) {
                    var res = $.parseJSON(data);
                    if( res.status == 1 ){
                    
                      swal({
                        title: "Good job!",
                        text: res.message,
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                      }, function() {
                          $('#modal-add-role').modal('hide');
                          dt_roles.search('').draw();
                          dt_admin.search('').draw();
                      });  
                    
                    }else{
                      swal({
                        title: "Oops..!",
                        text: res.message,
                        confirmButtonColor: "#EF5350",
                        type: "error"
                      }); 
                    }
                  },
                  error: function (data) {
                    swal({
                      title: "Oops! Something went wrong",
                      text: data.responseJSON.errors.name[0],
                      confirmButtonColor: "#EF5350",
                      type: "error"
                    });
                  },
              });
              return false;
      });

       $('#frmEditRole').on('submit',function (ev) {
              ev.preventDefault();

                  swal({
                    title: "Are you sure you want to update?",
                    text: "All existing users with this role would be affected.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: "Update",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm) {
                          var frm = $('#frmEditRole');
                          $.ajax({
                            type: frm.attr('method'),
                            url: frm.attr('action'),
                            data: frm.serialize(),
                            beforeSend: function(){
                              $('body').addClass('wait-pointer');
                            },
                            complete: function(){
                              $('body').removeClass('wait-pointer');
                            },
                            success: function (data) {
                              var res = $.parseJSON(data);
                              if( res.status == 1 ){
                              
                                swal({
                                  title: "Good job!",
                                  text: res.message,
                                  confirmButtonColor: "#66BB6A",
                                  type: "success"
                                }, function() {
                                    $('#modal-edit-role').modal('hide');
                                    dt_roles.search('').draw();
                                    dt_admin.search('').draw();
                                });  
                              
                              }else{
                                swal({
                                  title: "Oops..!",
                                  text: res.message,
                                  confirmButtonColor: "#EF5350",
                                  type: "error"
                                }); 
                              }
                            },
                            error: function (data) {
                              swal({
                                title: "Oops! Something went wrong",
                                text: data.responseJSON.errors.name[0],
                                confirmButtonColor: "#EF5350",
                                type: "error"
                              });
                            },
                        });
                        return false;
                    }
                });

             
      });

        $('#frmUpdateAccountRoles').on('submit',function (ev) {
              ev.preventDefault();
                swal({
                    title: "Are you sure you want to update?",
                    text: "Some existing user\'s role of " + account_name + " might be affected.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#EF5350",
                    confirmButtonText: "Update",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if (isConfirm) {

                        var frm = $('#frmUpdateAccountRoles');
                          $.ajax({
                            type: frm.attr('method'),
                            url: frm.attr('action'),
                            data: frm.serialize(),
                            beforeSend: function(){
                              $('body').addClass('wait-pointer');
                            },
                            complete: function(){
                              $('body').removeClass('wait-pointer');
                            },
                            success: function (data) {
                              var res = $.parseJSON(data);
                              if( res.status == 1 ){
                              
                                swal({
                                  title: "Good job!",
                                  text: res.message,
                                  confirmButtonColor: "#66BB6A",
                                  type: "success"
                                }, function() {
                                    $('#modal-edit-account-role').modal('hide');
                                    dt_client.search('').draw();
                                });  
                              
                              }else{
                                swal({
                                  title: "Oops..!",
                                  text: res.message,
                                  confirmButtonColor: "#EF5350",
                                  type: "error"
                                }); 
                              }
                            },
                            error: function (data) {
                              swal({
                                title: "Oops! Something went wrong",
                                text: data.responseJSON.errors.name[0],
                                confirmButtonColor: "#EF5350",
                                type: "error"
                              });
                            },
                        });
                        return false;
                    }
                });
      });
</script>
@endsection 
