<table class="table" style="margin-top: 40px;">
	<tr>
		<th>Role</th><th>Rate</th><th>Action</th>
	</tr>
	@forelse($data as $row)
	<tr>
		<td>{{$row->role_title}}</td>
		<td>@if( !empty($row->billing_rate) )$ {{$row->billing_rate}}@else $ 0.00 @endif</td>
		<td><a class="btn btn-success btn-xs"  onclick="update_billing({{ $row->id }});"><i class="icon-pencil4"></i></a></td>
	</tr>
	@empty
	<tr><td colspan="3">No data found.</td></tr>
	@endforelse
</table>

