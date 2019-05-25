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

                  <div class="tab-pane" id="accounts-tab">
                    <form class="form-horizontal">
                      <div class="col-lg-12">
                        <div class="form-group form-group-lg">
                          <div class="col-lg-4">
                            <select class="form-control input-lg"  id="_account" onchange="get_account_roles(this.value)">
                              <option value="" selected disabled>Select Account</option>
                              @foreach($accounts as $row)
                              <option value="{{ Crypt::encrypt($row->id) }}">{{ $row->account_name }}</option>
                              @endforeach
                            </select> 

                          </div>
                          <div class="col-lg-4">
                              <a id="btn-edit-roles" href="javascript:show_edit_role_modal();" class="btn btn-primary hidden"><i class="icon-pencil5"></i> Edit Roles</a>
                            </div>
                        </div>
                        <hr>
                        <table class="table tbl-client-roles" cellspacing="0" width="100%">
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
  <div id="modal_default modal-add-md" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data-add-md">

      </div>
    </div>
  </div>

  <div id="modal-update-md" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content content-data-update-md">

      </div>
    </div>
  </div>

   <div id="modal-edit-role" class="modal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content content-edit-role">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Edit Roles</h5>
          </div>
          <form class="form-horizontal" id="frmUpdateAccountRoles" method="POST" action="">
          @csrf
          <input type="hidden" id="role_account_id" />
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

    <div id="modal-add-role" class="modal" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header bg-primary">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title">Add Role</h5>
          </div>
          <form id="frmAddRole" class="form-horizontal" method="POST" action="">
            {{ csrf_field() }}
            <div class="modal-body">
              <fieldset class="content-group">
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Role Title :</label>
                  <div class="col-lg-9">
                    <input id="txtRoleName" type="text" class="form-control" name="name" value="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-3 text-right">Description :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="description" value="">
                  </div>
                </div>  

                <div class="row well">
                  <ul class="checktree">
                   @foreach($permissions as $key => $permission)
                      <li>
                        <input id="{{ $key }}" type="checkbox" /> <label for="{{ $key }}">{{ $key }}</label>
                        <ul>
                          @foreach($permission as $row)
                           
                          <li><input id="{{ $row['name'] }}" type="checkbox" /> <label for="{{ $row['name'] }}">{{ $row['description'] }}</label></li>
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
@endsection  

@section('script')
<script type="text/javascript" src="{{ asset('assets/js/plugins/trees/checktree.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/inputs/duallistbox.min.js') }}"></script>
<script type="text/javascript">

  $(function(){
    $("ul.checktree").checktree();
    $('.checktree input').uniform();
  });
  var dt_admin,dt_client,dt_roles,dt_permissions;

    $(".menu-curacall li").removeClass("active");
    $(".menu-admin-console-roles").addClass('active');

    dt_admin = $('.tbl-admin-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('admin/admin-roles') }}",
      columns: [
        {data: 'name'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    }); 

    dt_client = $('.tbl-client-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ url('admin/client-roles') }}",
      columns: [
        {data: 'name'},
        {data: 'description'},
        {data: 'action', orderable: false, searchable: false}
      ]
    });

    dt_roles = $('.tbl-roles').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      "language": {
        "search": " Search : "
      },
      ajax: "{{ route('admin.roles.fetch') }}",
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

  function admin_role_md(id) { 
    swal({
      title: "For your information",
      text: "This function is not yet available.",
      confirmButtonColor: "#2196F3",
      type: "info"
    });
  }
  
  function client_role_md(id) { 
    var account = document.getElementById('_account').value;
    if( account == "" ){
      swal({
        title: "Oops..!",
        text: "Please select an account.",
        confirmButtonColor: "#FF5722",
        type: "warning"
      });
    }else{
      $.ajax({ 
        type: "POST", 
        url: "{{ url('update-client-role-md') }}",
        data: { 
          _token : '{{ csrf_token() }}',
          id : id,
          account : account
        },
        success: function (data) {  
          $(".content-data-update-md").html( data );
          $('#modal-update-md').modal('show');
        },
        error: function (data){
          swal({
            title: "Oops..!",
            text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.",
            confirmButtonColor: "#EF5350",
            type: "error"
          });
        }
      });
    }
  }

  function get_account_roles(id)
  {
      $('#role_account_id').val(id);
      $('#btn-edit-roles').removeClass('hidden');
       $('#cmbAccountRoles').empty().trigger('change');
      @foreach($roles as $role)
          $("#cmbAccountRoles").append("<option value='{{ $role->id }}'>{{ $role->description }}</option>");
      @endforeach

      $('#cmbAccountRoles').bootstrapDualListbox('refresh', true);
      dt_client.ajax.url("{{ url('admin/client-roles') }}?id="+id).load();
  }

  function show_edit_role_modal()
  {
      // $(".content-data-update-md").html( data );
      $.ajax({
            url: "", 
            type: "GET",             
            data: { account_id : $('#_account').val() },      
            beforeSend: function(){
                  $('body').addClass('wait-pointer');
            },
            complete: function(){
              $('body').removeClass('wait-pointer');
            },          
            success: function(data) {

              var obj = $.parseJSON(data);
              console.log(obj);
                    //$('#cmbAccountRoles').empty().trigger('change');
                    $.each(obj, function(i, item) {
                      $("#cmbAccountRoles option[value='"+item.id+"']").remove();
                        $("#cmbAccountRoles").append("<option value='"+item.id+"' selected='selected'>"+item.description+"</option>");
                    });

                    $('#cmbAccountRoles').bootstrapDualListbox('refresh', true);
                    // $('#application-form-container').html('');
                    // $('#form_id').trigger('change'); 

                    // <option value="option1" selected="selected">Account Admin</option>
                    // <option value="option2">Agency Caregiver</option>
                    // <option value="option4">Agency Coordinator</option>
                    // <option value="option5" selected="selected">Agency Management</option>
                    // <option value="option7">Agency Nursing</option>
              var selectedAccount = $('#_account option:selected').html();
              $('#account-role').html(selectedAccount+ '\'s Roles');
              $('#modal-edit-role').modal('show');

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

   // Disable filtering
    $('.listbox-filter-disabled').bootstrapDualListbox({
        showFilterInputs: false
    });

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
                      title: "Oops...",
                      text: "No connection could be made because the target machine actively refused it. Please refresh the browser and try again.!",
                      confirmButtonColor: "#EF5350",
                      type: "error"
                    });
                  },
              });
              return false;
      });
</script>
@endsection 
