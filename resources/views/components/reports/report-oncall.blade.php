<div class="container-fluid">
    <div class="row">
        <div class="form-group form-group-xs col-sm-3">
            <select class="form-control oncall-user">
                <option value="all">Select On call</option>
                @foreach($users as $row) 
                <option @if($account_id == $row->id) selected  @endif value="{{$row->id}}">{{ $row->fname.' '.$row->lname  }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group form-group-xs col-sm-3">
            <input type="text" class="form-control daterange-basic date-range-val" 
            @if(!empty($range)) value="{{$range}}" 
            @else value="{{ date ( 'm/01/Y' ) }} - {{ date ( 'm/d/Y' ) }}"
            @endif
            >
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 active-case-report" style="border: 5px solid #03a9f4; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Active</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($active_count)
                <a class="btn-active-case">{{$active_count->total}}</a>
              @endisset 
            </span>
        </div>
        <div class="col-sm-3 pending-case-report" style="border: 5px solid #f44336; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Pending</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($pending_count)
                <a class="btn-pending-case">{{$pending_count->total}}</a>
              @endisset 
            </span>
        </div>
        <div class="col-sm-3 closed-case-report" style="border: 5px solid #4caf50; padding: 10px">
            <span class="text-semibold" style="margin: 10px; font-size: 15px;">Closed</span><br>
            <span class="text-semibold" style="margin: 10px; font-size: 30px;">
              @isset($closed_count)
                <a class="btn-closed-case">{{$closed_count->total}}</a>
              @endisset 
            </span>
        </div>
    </div>
    <div class="row content-list-table">
      
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default'
    });
  }); 

  $( ".btn-active-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-active-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
      },
      success: function (data) {  
        $(".content-list-table").html( data );
        // alert(data);
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
  });

  $( ".btn-pending-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-pending-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
      },
      success: function (data) {  
        $(".content-list-table").html( data );
        // alert(data);
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
  });

  $( ".btn-closed-case" ).click(function() {
    // alert($(".oncall-user").val());
    $.ajax({  
      type: "POST", 
      url: "{{ url('report-closed-case-list') }}", 
      data: {   
        _token : '{{ csrf_token() }}',
        user_id : $(".oncall-user").val(),
        range : $(".date-range-val").val()
      },
      success: function (data) {  
        $(".content-list-table").html( data );
        // alert(data);
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
  });

  $( ".date-range-val" ).change(function() {
    reportOncall($(".oncall-user" ).val(),$( ".date-range-val" ).val());
  });
  $( ".oncall-user" ).change(function() {
    reportOncall($(this).find(":selected").val(),$( ".date-range-val" ).val());
  });
</script>