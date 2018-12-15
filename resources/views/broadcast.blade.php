@extends('layouts.app')
@section('css')
<style type="text/css">
  .form-horizontal .checkbox .checker{
    top: 16px;
  }
</style>
@endsection
@section('content')
    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Broadcast</span></h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
                <li class="active">Broadcast</li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
    <div class="content">
        <div class="panel panel-flat">
              <div class="panel-heading">
                <h5 class="panel-title">Broadcast Messsage</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                        <li><a data-action="reload"></a></li>
                        <li><a data-action="close"></a></li>  
                    </ul>
                </div>
              </div>
              <div class="panel-body">
             <!--  <h6 class="text-semibold">Broadcast Messsage</h6> -->
              <button type="submit" class="btn btn-primary">Create Group</button>

                <form class="form-horizontal" id="form-user-info">
                  <div class="form-group">
                    <label class="control-label col-lg-2 text-right">Send To :</label>
                    <div class="col-lg-10">
                      <div class="multi-select-full">
                        <select class="form-control multiselect-sm" multiple="multiple" name="account_id" required>
                          @foreach($accounts as $row)
                          <option value="{{ $row->id }}">{{ $row->account_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2 text-right">Message :</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="text-right">
                    <button type="reset" class="btn btn-link">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                  </div>
                </form>
              
            </div>
        </div>

      <!-- <div class="col-md-6">
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h5 class="panel-title">Create Group</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
          </div>
          <div style="padding: 0 20px;">
            <table>
              <tr style="border-bottom: 1px solid #ddd;">
                  <td width="400" style="padding-bottom: 10px;">
                      <h4>Create Group</h4>
                      Create a group for sending to multiple contacts.
                  </td>
                  <td valign="bottom" style="padding-bottom: 10px;">
                      <button type="button" class="btn btn-primary" style="width: 80px;">Create</button>
                  </td>
              </tr> 
            </table>
          </div>

        </div>
      </div> -->
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
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-broadcast").addClass('active'); 

    $('.multiselect-sm').multiselect({
      buttonClass: 'btn btn-default btn-sm'
    });
    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

     // Initialize multiple switches
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }
    else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }
  }); 

</script>
@endsection 
