@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">User Account Settings</span></h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
      <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
      <li class="active">User Account Settings</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="panel panel-flat">
    <div class="panel-body"> 
      <div class="tabbable">
        <ul class="nav nav-tabs nav-tabs-highlight">
          <li class="active"><a href="#highlighted-justified-tab1" data-toggle="tab" id="tab1">Profile Information</a></li>
          <li><a href="#highlighted-justified-tab2" data-toggle="tab" id="tab2">Security and Login</a></li>
          <li><a href="#highlighted-justified-tab3" data-toggle="tab" id="tab3">Message Settings</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="highlighted-justified-tab1">
            <form class="form-horizontal" id="form-user-info" enctype="multipart/form-data">
              <div class="col-lg-8">
                {{ csrf_field() }}
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">CuraCall ID :</label>
                  <div class="col-lg-9">
                    <label class="control-label text-semibold">{{ 'CC'.str_pad($data[0]->id, 6,'0',STR_PAD_LEFT)  }}</label>
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">
                  <span class="text-danger">*</span>
                  First Name :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="fname" value="{{ $data[0]->fname }}" required>
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">
                  <span class="text-danger">*</span>
                  Last Name :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="lname" value="{{ $data[0]->lname }}" required>
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">Prof. Suffix :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="prof_suffix" value="{{ $data[0]->prof_suffix }}" placeholder="ex. MD, PA, RN">
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">Title :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="title" value="{{ $data[0]->title }}">
                  </div>
                </div>

                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">
                  <span class="text-danger">*</span>
                  Email :</label>
                  <div class="col-lg-9">
                    <input type="email" class="form-control" name="email" value="{{ $data[0]->email }}" required>
                  </div>
                </div>

                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">Mobile no :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="mobile_no" value="{{ $data[0]->mobile_no }}">
                  </div>
                </div>

                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-3 text-right">Phone no :</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" data-mask="(999) 999-9999" name="phone_no" value="{{ $data[0]->phone_no }}">
                  </div>
                </div>

                <div class="text-right">
                  <button type="reset" class="btn btn-link" value="reset">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </div>
              <div class="col-lg-3"> 
                <div style="border: 1px solid #d0d0d0; border-radius: 3px; margin-bottom: 10px; width: 250px; height: 250px;"> 
                  <img id="output" src="{{ asset('storage/uploads/users/'.$data[0]->prof_img.'?v='.strtotime('now')) }}" style="width:250px;height:250px; border-radius: 3px;" />
                </div>
                <input type="file" accept="image/*"  name="image" onchange="loadFile(event)">
              </div>
            </form>

          </div>
          <div class="tab-pane" id="highlighted-justified-tab2">
            <div class="col-lg-7">
              <form class="form-horizontal" id="form-user-credentials">
                {{ csrf_field() }}
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-4 text-right">Current Password :</label>
                  <div class="input-group col-lg-8">
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                    <span class="input-group-addon"><i class="icon-eye" onmouseover="password_show('current_password')" onmouseout="password_hide('current_password')"></i></span>
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-4 text-right">New Password :</label>
                  <div class="input-group col-lg-8">
                    <input type="password" class="form-control" name="password" id="password" onkeyup="validate_password(this)" required> 
                    <span class="input-group-addon"><i class="icon-eye" onmouseover="password_show('password')" onmouseout="password_hide('password')"></i></span>
                  </div>
                </div>
                <div class="form-group form-group-xs">
                  <label class="control-label col-lg-4 text-right">Confirm New Password :</label>
                  <div class="input-group col-lg-8">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    <span class="input-group-addon"><i class="icon-eye" onmouseover="password_show('password_confirmation')" onmouseout="password_hide('password_confirmation')"></i></span>
                  </div>
                </div>
                <div class="text-right">
                  <button type="reset" class="btn btn-link" value="reset">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </form>
            </div> 
            <div class="col-lg-5" style="padding-left: 30px;">
              <p id="val_1">Password must at least 8 characters long.</p>
              <p id="val_6">Password must contain at least:</p>
              <p id="val_2">One (1) upper case letter.</p>
              <p id="val_3">One (1) lower case letter.</p>
              <p id="val_4">One (1) number.</p>
              <p id="val_5">One (1) special character.</p> 
            </div>
          </div>
          <div class="tab-pane" id="highlighted-justified-tab3">
            <div class="checkbox checkbox-switch">
              <label>
                <input type="checkbox" class="switch" name="msg_all" data-on-text="On" data-off-text="Off">
                Web Notifications
              </label>
            </div>
          </div>
          <!-- accounts -->
        </div>
      </div>
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
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-user-account-settings").addClass('active');

    $(".switch").bootstrapSwitch();

    $( "#form-user-info" ).submit(function( e ) {
      $.ajax({ 
        type: "POST", 
        url: "{{ url('user-account-settings/update-user-info') }}",
        data: new FormData(this),
        contentType: false,
        processData:false,
        success: function (data) {
          var res = $.parseJSON(data);
          if( res.status == 1 ){
            swal({
              title: "Good job!",
              text: res.message,
              confirmButtonColor: "#66BB6A",
              type: "success"
            }); 
          }else if( res.status == 2 ){
            var error_message="";
            $.each(res.message,function(index,item){
              error_message+=item+",";
            }); 
            var error_message = error_message.replace(/,/g, "\n")
            swal({
              title: "Oops...",
              text: error_message,
              confirmButtonColor: "#EF5350",
              type: "error"
            });
          }else{
            swal({
              title: "Oops...",
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
      e.preventDefault();
    });

    $( "#form-user-credentials" ).submit(function( e ) {
      $.ajax({ 
        type: "POST", 
        url: "{{ url('user-account-settings/update-user-credentials') }}",
        data: $('#form-user-credentials').serialize(),
        success: function (data) {
          var res = $.parseJSON(data);
          if( res.status == 1 ){
            swal({
              title: "Good job!",
              text: res.message,
              confirmButtonColor: "#66BB6A",
              type: "success"
            }); 
          }else if( res.status == 2 ){ 
            var error_message="";
            $.each(res.message,function(index,item){
              error_message+=item+",";
            }); 
            var error_message = error_message.replace(/,/g, "\n")
            swal({
              title: "Oops...",
              text: error_message,
              confirmButtonColor: "#EF5350",
              type: "error"
            }); 
          }else if( res.status == 3 ){
            swal({
              title: "Oops...",
              text: error_message,
              confirmButtonColor: "#EF5350",
              type: "error"
            });
          }else{
            swal({
              title: "Oops...",
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
      e.preventDefault();
    });

  }); 


  var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }


  function validate_password(txt){
    var number = /\d+/g;
    var upper_case = /[A-Z]/g;
    var lower_case = /[a-z]/g;
    var special_case = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
    

    
    if( txt.value.length >= 8 ){
      document.getElementById("val_1").innerHTML = document.getElementById("val_1").innerHTML.strike();
    }else{
      document.getElementById("val_1").innerHTML="Password must at least 8 characters long.";
    }

    if( txt.value.match(upper_case) ){
      document.getElementById("val_2").innerHTML = document.getElementById("val_2").innerHTML.strike();
    }else{ 
      document.getElementById("val_2").innerHTML="One (1) upper case letter.";
    }

    if( txt.value.match(lower_case) ){
      document.getElementById("val_3").innerHTML = document.getElementById("val_3").innerHTML.strike();
    }else{ 
      document.getElementById("val_3").innerHTML="One (1) lower case letter.";
    }

    if( txt.value.match(number) ){
      document.getElementById("val_4").innerHTML = document.getElementById("val_4").innerHTML.strike();
    }else{ 
      document.getElementById("val_4").innerHTML="One (1) number.";
    }

    if( txt.value.match(special_case) ){
      document.getElementById("val_5").innerHTML = document.getElementById("val_5").innerHTML.strike();
    }else{ 
      document.getElementById("val_5").innerHTML="One (1) special character.";
    }

    if( txt.value.match(upper_case) && txt.value.match(lower_case) && txt.value.match(number) && txt.value.match(special_case) ){
      document.getElementById("val_6").innerHTML = document.getElementById("val_6").innerHTML.strike();
    }else{ 
      document.getElementById("val_6").innerHTML="Password must contain at least:";
    }

    
  } 

  function password_show(field){
    document.getElementById(field).type = "text";
  }

  function password_hide(field){
    document.getElementById(field).type = "password";
  }



</script>
@endsection 
