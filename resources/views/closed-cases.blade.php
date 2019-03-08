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
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cases</span> - List</h4>
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
      <li><a href="#">Cases</a></li>
      <li class="active">Closed cases</li>
    </ul>
  </div>
</div>
<!-- /page header -->
<div class="content">
  <div class="panel panel-flat">
    <div class="table-responsive">
      <table class="table text-nowrap">
        <thead>
          <tr>
            <th style="width: 50px">Sitting Time</th>
            <th style="width: 150px;">User</th>
            <th>Description</th>
            <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
          </tr>
        </thead>
        <tbody>      
          <tr class="active border-double">
            <td colspan="3">Closed cases</td>
            <td class="text-right">
              <span class="badge bg-success">{{ $closed_count->total }}</span>
            </td>
          </tr>
          @forelse($cases as $row)    
            <tr>
              <td class="text-center">
                <i class="icon-checkmark3 text-success"></i>
              </td>
              <td>
                <div class="media-body">
                  <a href="{{ url('/cases/case_id',$row->id) }}" class="display-inline-block text-default letter-icon-title">{{ $row->sender_fullname }}</a>
                  <div class="text-muted text-size-small"><span class="status-mark border-success position-left"></span> Closed</div>
                </div>
              </td>
              <td>
                <a href="{{ url('/cases/case_id',$row->id) }}" class="text-default display-inline-block">
                  <span>[#{{ $row->case_id }}] Call type</span>
                  <span class="display-block text-muted">Full message of the case...</span>
                </a>
              </td>
              <td class="text-center">
                <span class="text-muted">
                  @if(!empty($row->created_at))
                    @if( date('Y-m-d') == date('Y-m-d', strtotime($row->created_at)))
                        {{  date_format($row->created_at,"h:i a") }}
                    @else
                        {{  date_format($row->created_at,"M d") }}
                    @endif
                  @endif
                </span>
              </td>
            </tr>
          @empty
          <tr class="unread"><td colspan="4">No active case(s) found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection  

@section('script')
<script type="text/javascript">
  $(".menu-curacall li").removeClass("active");
  $(".menu-cases").addClass('active');
  $(".submenu-curacall li").removeClass("active");
  $(".submenu-cases-closed-cases").addClass('active');
</script>

@endsection 

