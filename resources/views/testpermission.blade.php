@can('view-account-reports')
  view account reports block
@endcan
<br>
@if(auth()->user()->hasAnyPermission(['view-account-reports','view-oncall-reports']))
  has any permission
@endif
<br>
@if(auth()->user()->can('view-account-reports'))
  another  view account reports block
@endif

<br>
@role('curacall-admin')
    I am a Curacall admin
@else
    I am not Curacall admin
@endrole