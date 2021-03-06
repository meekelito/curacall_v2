<table class="table" style="margin-top: 40px;">
	<tr class="bg-primary">
		<td>Account</td>
		<td>User Role</td>
		<td>No. of User</td>
		<td>{{date("M", strtotime($billing_month ."-1 months"))}} Prorate Users</td>
		<td>{{date("M", strtotime($billing_month ."-1 months"))}} Billing Prorate</td>
		<td align="center">{{date('M', strtotime($billing_month))}} Prorate Users</td>
		<td align="right">{{date('M', strtotime($billing_month))}} Billing</td>
		<td align="right">Total</td>
	</tr>
	@php 
	$account_holder = ""; 
	$account_role = ""; 
	$ctr_role = 0;
	$ctr = 0;
	$user_count = 1;
	$ctr_past_month_prorate = 0;
	$total_past_month_prorate = 0;
	$ctr_curr_month_prorate = 0;
	$total_curr_month_prorate = 0;
	

	$total_user_count = 0;
	$total_ctr_past_month_prorate = 0;
	$gtotal_past_month_prorate = 0;
	$total_ctr_curr_month_prorate = 0;
	$gtotal_curr_month_prorate = 0;
	$grand_total = 0;

	@endphp
	@forelse($users as $user)
	@php
		$date1 = date('Y-m-d', strtotime($user->date_activated)); 
		$date2 = $billing_month;
		$date3 = date('Y-m-d', strtotime($billing_month ."-1 months"));
		$d3 = explode('-', $date3);
		$d3_day = $d3[2];
		$d3_month = $d3[1];
		$d3_year = $d3[0];
		$d1 = explode('-', $date1);
		$d1_day = $d1[2];

		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $d3_month, $d3_year);
		$days_consumed = $days_in_month - $d1_day;
		$past_month_prorate = ($user->billing_rate / $days_in_month) * $days_consumed;
		$past_month_prorate = number_format($past_month_prorate, 2, '.', '');
	@endphp

	@if( $account_holder == $user->account_name && $account_role == $user->role_title)
		@php
		$user_count++;
		@endphp
	@else
		@if($ctr != 0)
			<tr>
				<td>{{$account_holder}}</td>
				<td>{{$account_role}}</td>
				<td>{{$user_count}}</td>
				<td>{{$ctr_past_month_prorate}}</td>
				<td>${{number_format($total_past_month_prorate, 2, '.', '') }}</td>
				<td align="center">{{$ctr_curr_month_prorate}}</td>
				<td align="right">${{ number_format($total_curr_month_prorate, 2, '.', '') }}</td>
				<td align="right">${{ number_format($total_past_month_prorate+$total_curr_month_prorate, 2, '.', '')}} </td>
			</tr>


			@php
			$total_user_count+=$user_count;
			$total_ctr_past_month_prorate+=$ctr_past_month_prorate;
			$gtotal_past_month_prorate+=$total_past_month_prorate;
			$total_ctr_curr_month_prorate+=$ctr_curr_month_prorate;
			$gtotal_curr_month_prorate+=$total_curr_month_prorate;
			$grand_total += $total_past_month_prorate+$total_curr_month_prorate;
			

			$user_count=1;
			$ctr_past_month_prorate = 0; 
			$total_past_month_prorate = 0;
			$ctr_curr_month_prorate = 0;
			$total_curr_month_prorate = 0;
			@endphp
		@endif
		
		
	@endif 



	@if( $account_holder != $user->account_name && $account_role != $user->account_role && $ctr != 0)
		<tr class="bg-blue">
			<td></td>
			<td>Total</td>
			<td>{{$total_user_count}}</td>
			<td>{{$total_ctr_past_month_prorate}}</td>
			<td>${{ number_format($gtotal_past_month_prorate, 2, '.', '') }}</td>
			<td align="center">{{ $total_ctr_curr_month_prorate}}</td>
			<td align="right">${{ number_format($gtotal_curr_month_prorate, 2, '.', '') }}</td>
			<td align="right">${{ number_format($grand_total, 2, '.', '') }}</td>
		</tr>
		@php 
		$ctr_past_month_prorate = 0; 
		$total_past_month_prorate = 0;
		$ctr_curr_month_prorate = 0;
		$total_curr_month_prorate = 0;
		$total_ctr_curr_month_prorate = 0;

		$total_user_count = 0;
		$total_ctr_past_month_prorate = 0;
		$gtotal_past_month_prorate = 0;
		$gtotal_curr_month_prorate = 0;
		$grand_total = 0;
		@endphp
	@endif 



	@if($date1 < $date2 && $date1 > $date3 ) 
		@php $ctr_past_month_prorate++; @endphp
	@endif

	@if($date1 < $date2 && $date1 > $date3) 
		@php $total_past_month_prorate+=$past_month_prorate; @endphp
	@endif 

	@if($date1 <= $date2) 
		@php $ctr_curr_month_prorate++; @endphp
	@endif

	@if($date1 <= $date2) 
		@php $total_curr_month_prorate+=$user->billing_rate; @endphp
	@endif

	@if($date1 < $date2 && $date1 > $date3) 
	@else
		@php $past_month_prorate = 0; @endphp
	@endif

	@php 
		$account_holder = $user->account_name; 
		$account_role = $user->role_title; 
		$ctr++;
		$ctr_role++;
	@endphp


	@if ($loop->last) 
		@php
			$total_user_count+=$user_count;
			$total_ctr_past_month_prorate+=$ctr_past_month_prorate;
			$gtotal_past_month_prorate+=$total_past_month_prorate;
			$total_ctr_curr_month_prorate+=$ctr_curr_month_prorate;
			$gtotal_curr_month_prorate+=$total_curr_month_prorate;
			$grand_total += $total_past_month_prorate+$total_curr_month_prorate;
		@endphp
		<tr>
			<td>{{$account_holder}}</td>
			<td>{{$account_role}}</td>
			<td>{{$user_count}}</td>
			<td>{{$ctr_past_month_prorate}}</td>
			<td>${{ number_format($total_past_month_prorate, 2, '.', '') }}</td>
			<td align="center">{{$ctr_curr_month_prorate}}</td>
			<td align="right">${{ number_format($total_curr_month_prorate, 2, '.', '') }}</td>
			<td align="right">
				${{ number_format($total_past_month_prorate+$total_curr_month_prorate, 2, '.', '') }}
			</td>
		</tr>
	@endif 
	@if ($loop->last)
	<tr class="bg-blue">
		<td></td>
		<td>Total</td>
		<td>{{$total_user_count}}</td>
		<td>{{$total_ctr_past_month_prorate}}</td>
		<td>${{$gtotal_past_month_prorate}}</td>
		<td align="center">{{$total_ctr_curr_month_prorate}}</td>
		<td align="right">${{number_format($gtotal_curr_month_prorate, 2, '.', '')}}</td>
		<td align="right">${{ number_format($grand_total, 2, '.', '') }}</td>
	</tr>
	@endif 


	@empty
	<tr><td colspan="3">No data found.</td></tr>
	@endforelse
</table>
