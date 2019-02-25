@extends('layouts.app')

@section('css')
  <style type="text/css">

  </style>
@endsection 

@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Case</span></h4>
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
      <li><a href="#l"><i class="icon-home2 position-left"></i> Home</a></li>
      <li><a href="#">Case</a></li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="row">
    <div class="col-lg-8">
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h5 class="panel-title">Case Information</h5>
          <div class="heading-elements">
            <div class="btn-group navbar-btn">
              <a href="#" class="btn btn-default"><i class="icon-forward"></i> <span class="hidden-xs position-right">Forward</span></a>
              <a href="#" class="btn btn-default"><i class="icon-checkmark4"></i> <span class="hidden-xs position-right">Close</span></a>
            </div>
          </div>
        </div>
        
        <table class="table"> 
          <tr class="bg-blue"><td colspan="2">Caller Information</td></tr>
          <tr><td width="200">First Name:</td><td>Tara</td></tr>
          <tr><td>Last Name:</td><td>Davis</td></tr>
          <tr><td>Caller Type:</td><td>Caregiver</td></tr>
          <tr class="bg-blue"><td colspan="2">Type of Caregiver Home Health Aide (HHA)</td></tr>
          <tr><td>Caller Telephone Number:</td><td>212-098-7654</td></tr>
          <tr><td>Is Hospital Related:</td><td>No</td></tr>
          <tr><td>Is Clock-in Code Available:</td><td>Does Not Know</td></tr>
          <tr class="bg-blue"><td colspan="2">Call Information</td></tr>
          <tr><td>Call Type:</td><td>Office; Payroll</td></tr>
          <tr><td>Payroll concern:</td><td>Was not paid Correct Amount</td></tr>
          <tr><td>Action:</td><td>Take a Message and Send Email</td></tr>
          <tr class="bg-blue"><td colspan="2">Caregiver Information</td></tr>
          <tr><td>Type of Caregiver:</td><td>Home Health Aide (HHA)</td></tr>
          <tr><td>First Name:</td><td>Tara</td></tr>
          <tr><td>Last Name:</td><td>Davis</td></tr>
          <tr><td>Telephone Number:</td><td>212-098-7654</td></tr>
          <tr><td>Provide start time of shift:</td><td>Not Applicable</td></tr>
          <tr class="bg-blue"><td colspan="2">Other Information</td></tr>
          <tr><td>Full Message:</td><td>HHA called and wants to speak with Payroll in regards to incorrect amount on her paycheck Please give her a call back as soon as possible as she is currently in the bank.</td></tr>
          <tr><td>Call Language:</td><td>Russian</td></tr>
          <tr><td>Contact Translation Company:</td><td>Yes</td></tr>
          <tr><td>Number of Calls:</td><td>1st Time</td></tr>
          <tr class="bg-blue"><td colspan="2">Case Create</td></tr>
          <tr><td>Date/Time:</td><td>11/30/2018 02:27 PM</td></tr>
          <tr><td>Created By:</td><td>Kristina Valerio</td></tr>
          <tr><td>Case Sent Date/Time:</td><td>11/30/2018 02:37 PM</td></tr>
        </table>

      </div>
    </div>
    <div class="col-lg-4">
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h5 class="panel-title">Notes</h5>
        </div>
        <table class="table">
          <tr><td colspan="2">No note(s) found.</td></tr>
        </table>
        
      </div>
    </div>
  </div>
</div>
@endsection  

@section('script')
<script type="text/javascript">
  $(".menu-curacall li").removeClass("active");
  $(".menu-cases").addClass('active');
  // $(".submenu-curacall li").removeClass("active");
  // $(".submenu-cases-all-cases").addClass('active');
</script>

@endsection 
