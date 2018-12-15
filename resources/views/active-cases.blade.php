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
            <li class="active">Active cases</li>
        </ul>
    </div>
</div>
<!-- /page header -->

<div class="col-lg-12">
    <!-- Single line -->
    <div class="panel panel-white">

        <div class="panel-toolbar panel-toolbar-inbox">
            <div class="navbar navbar-default">
                <ul class="nav navbar-nav visible-xs-block no-border">
                    <li>
                        <a class="text-center collapsed" data-toggle="collapse" data-target="#inbox-toolbar-toggle-single">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </li>
                </ul>

                <div class="navbar-collapse collapse" id="inbox-toolbar-toggle-single">


                    <div class="btn-group navbar-btn">
                         <a href="{{ url('/new-message') }}" type="button" class="btn btn-default"><i class="icon-pencil7"></i> <span class="hidden-xs position-right">New</span></a>
                        <button type="button" class="btn btn-default"><i class="icon-bin"></i> <span class="hidden-xs position-right">Delete</span></button>
                    </div>

                    <div class="navbar-right">
                        <p class="navbar-text"><span class="text-semibold">1-5</span> of <span class="text-semibold">5</span></p>
                        <div class="btn-group navbar-left navbar-btn">
                            <button type="button" class="btn btn-default btn-icon disabled"><i class="icon-arrow-left12"></i></button>
                            <button type="button" class="btn btn-default btn-icon disabled"><i class="icon-arrow-right13"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        
        <div class="table-responsive">
            <table class="table table-inbox">
                <tbody data-link="row" class="rowlink">
                    @forelse($cases as $row)
                        <tr class="unread">
                            <td class="table-inbox-checkbox rowlink-skip">
                                <input type="checkbox" class="styled">
                            </td>
                            <td class="table-inbox-image">
                                <img src="{{ asset('storage/uploads/users/'.$row->prof_img.'?v='.strtotime('now')) }}" class="img-circle img-sm" alt="">
                            </td>
                            <td class="table-inbox-name">
                                <a class="pjax-link" data-pjax="#content" href="{{ url('/reply?room='.$row->room_id) }}"> 
                                    <div class="letter-icon-title text-default">{{ ucwords($row->fname.' '.$row->lname) }}</div>
                                </a>
                            </td>
                            <td class="table-inbox-message">
                               <!--  <span class="table-inbox-subject">Lorem ipsum dolor sit amet, &nbsp;-&nbsp;</span> -->
                                <!-- <span class="table-inbox-preview"></span> -->
                                <span class="table-inbox-subject">{{ $row->message }}</span>
                            </td>
                            <td class="table-inbox-attachment">
                                <!-- <i class="icon-attachment text-muted"></i> -->
                            </td>
                            <td class="table-inbox-time">
                            @if(!empty($row->created_at))
                                @if( date('Y-m-d') == date('Y-m-d', strtotime($row->created_at)))
                                    {{  date_format($row->created_at,"h:i a") }}
                                @else
                                    {{  date_format($row->created_at,"M d") }}
                                @endif
                            @endif
                            </td>
                        </tr>
                    @empty
                    <tr class="unread"><td>No active case(s) found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- /single line -->
</div>
@endsection  

@section('script')
<script type="text/javascript">
  $(".menu-curacall li").removeClass("active");
  $(".menu-cases").addClass('active');
  $(".submenu-curacall li").removeClass("active");
  $(".submenu-cases-active-cases").addClass('active');
</script>

@endsection 
