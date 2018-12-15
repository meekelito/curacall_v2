@extends('layouts.app')

@section('content')
  <!-- Page header -->
  <div class="page-header page-header-default">
    <div class="page-header-content">
      <div class="page-title">
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Directory</span></h4>
      </div>
    </div>
    <div class="breadcrumb-line">
      <ul class="breadcrumb">
        <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
        <li class="active">Directory</li>
      </ul>
    </div>
  </div>
  <!-- /page header -->
  <div class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-flat">
          <div class="panel-heading">
              <h5 class="panel-title">Directory</h5>
              <div class="heading-elements">
                  <ul class="icons-list">
                      <li><a data-action="collapse"></a></li>
                      <li><a data-action="reload"></a></li>
                      <li><a data-action="close"></a></li>
                  </ul>
              </div>
          </div>
     
          <ul class="media-list media-list-linked">
            
            @php
              $role = "";
            @endphp
            @forelse($data as $row)
              @if($role != $row->role_title)
                <li class="media-header">{{$row->role_title}}</li>
              @else

              @endif
              <li class="media">
              <div class="media-link cursor-pointer" data-toggle="collapse" data-target="#{{$row->id}}">
                <div class="media-left">
                  @if( file_exists('storage/uploads/users/'.$row->prof_img) )
                  <img src="{{ asset('storage/uploads/users/'.$row->prof_img) }}" class="img-circle img-sm" alt="">
                  @else
                  <img src="{{ asset('storage/uploads/users/default.png') }}" class="img-circle img-sm"  alt="">
                  @endif
                </div>
                <div class="media-body">
                  <div class="media-heading text-semibold">{{ $row->fname." ".$row->lname}}</div>
                  <span class="text-muted">
                    @if( $row->is_curacall )
                      CuraCall
                    @else
                      {{ $row->account_name }}
                    @endif
                  </span>
                </div>
                <div class="media-right media-middle text-nowrap">
                  <i class="icon-menu7 display-block"></i>
                </div>
              </div>

              <div class="collapse" id="{{$row->id}}">
                <div class="contact-details">
                  <ul class="list-extended list-unstyled list-icons">
                    <li><i class="icon-user-tie position-left"></i> {{ $row->role_title }} </li>
                    <li><i class="icon-phone position-left"></i> {{ $row->phone_no }}</li>
                    <li><i class="icon-mail5 position-left"></i> <a href="#">{{ $row->email }}</a></li>
                  </ul>
                </div>
              </div>
            </li>
            @php
              $role = $row->role_title;
            @endphp

            @empty
              <li>No replies</li>
            @endforelse
            
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h5 class="panel-title">Search for Contacts</h5>
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
                  <td width="400" style="padding-right: 20px; padding-bottom: 10px; ">
                      <h4>Search for Users</h4>
                      <input type="text" class="form-control" placeholder="Search name, account or curacall">
                  </td> 
                  <td valign="bottom" style="padding-bottom: 10px;">
                      <button type="button" class="btn btn-primary" style="width: 80px;">Find</button>
                  </td>
              </tr>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection  

@section('script')
<script type="text/javascript">
  $(document).ready(function () {
    $(".menu-curacall li").removeClass("active");
    $(".menu-directory").addClass('active');
  }); 
</script>
@endsection 
