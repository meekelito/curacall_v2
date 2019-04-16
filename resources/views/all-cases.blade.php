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
      <li class="active">All cases</li>
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
          @php 
          $ctr=1;
          @endphp


          @forelse($cases as $row)

          @switch($row->status)
              @case(1)
                @if($row->status == '1' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="3">Active cases</td>
                  <td class="text-right">
                    <span class="badge bg-blue">{{ $active_count->total }}</span>
                  </td>
                </tr>
                @endif
                <tr>
                  <td class="text-center">
                    <h6 class="no-margin">20 <small class="display-block text-size-small no-margin">hours</small></h6>
                  </td>
                  <td>
                    <div class="media-body">
                      <a href="{{ url('/cases/case_id',$row->id) }}" class="display-inline-block text-default text-semibold letter-icon-title">{{ $row->sender_fullname }}</a>
                      <div class="text-muted text-size-small"><span class="status-mark border-blue position-left"></span> Active</div>
                    </div>
                  </td>
                  <td>
                    <a href="{{ url('/cases/case_id',$row->id) }}" class="text-default display-inline-block">
                      <span class="text-semibold">[#{{ $row->case_id }}] Call type</span>
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
                
                @if( $active_count->total == $ctr )
                  @php 
                    $ctr=0;
                  @endphp
                @endif
              @break

              @case(2)
                @if($row->status == '2' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="3">Pending cases</td>
                  <td class="text-right">
                    <span class="badge bg-danger">{{ $pending_count->total }}</span>
                  </td>
                </tr>
                @endif
                <tr>
                  <td class="text-center">
                    <i class="icon-cross2 text-warning"></i>
                  </td>
                  <td>
                    <div class="media-body">
                      <a href="{{ url('/cases/case_id',$row->id) }}" class="display-inline-block text-default">{{ $row->sender_fullname }}</a>
                      <div class="text-muted text-size-small"><span class="status-mark border-warning position-left"></span> Pending</div>
                    </div>
                  </td>
                  <td>
                    <a href="{{ url('/cases/case_id',$row->id) }}" class="text-default display-inline-block">
                      <span>[#{{ $row->case_id }}]  Call type</span>
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

                @if( $pending_count->total == $ctr )
                  @php 
                    $ctr=0;
                  @endphp
                @endif
              @break

              @case(3)
                @if($row->status == '3' && $ctr == 1 )
                <tr class="active border-double">
                  <td colspan="3">Closed cases</td>
                  <td class="text-right">
                    <span class="badge bg-success">{{ $closed_count->total }}</span>
                  </td>
                </tr>
                @endif
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
              @break

              @default
                  Default case...
          @endswitch
          @php $ctr++ @endphp
          

          @empty
          <tr class="unread"><td colspan="4">No case(s) found.</td></tr>
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
  $(".submenu-cases-all-cases").addClass('active');
  fetchCase();
</script>

@endsection 
