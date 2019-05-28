<table class="table" style="margin-top: 40px;">
	<tr class="bg-primary">
		<td>Account</td>
		<td>Active User</td>
		<td>Date Activated</td>
		<td>User Role</td>
		<td>{{date("M", strtotime($billing_month ."-1 months"))}} Prorate Users</td>
		<td>{{date("M", strtotime($billing_month ."-1 months"))}} Billing Prorate</td>
		<td>{{date('M', strtotime($billing_month))}} Prorate Users</td>
		<td>{{date('M', strtotime($billing_month))}} Billing</td>
		<td>Total</td>
	</tr>
	@php 
	$account_holder = ""; 
	$ctr = 0;
	$ctr_past_month_prorate = 0;
	$total_past_month_prorate = 0;
	$ctr_curr_month_prorate = 0;
	$total_curr_month_prorate = 0;
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
	@if( $account_holder != $user->account_name && $ctr != 0)
	<tr class="bg-blue">
		<td></td>
		<td>Total</td>
		<td></td>
		<td></td>
		<td>{{$ctr_past_month_prorate}}</td>
		<td>${{$total_past_month_prorate}}</td>
		<td align="center">{{$ctr_curr_month_prorate}}</td>
		<td align="right">${{ number_format($total_curr_month_prorate, 2, '.', '') }}</td>
		<td>${{$total_past_month_prorate+$total_curr_month_prorate}} </td>
	</tr>
	@php 
	$ctr_past_month_prorate = 0; 
	$total_past_month_prorate = 0;
	$ctr_curr_month_prorate = 0;
	$total_curr_month_prorate = 0;
	@endphp
	@endif 
	<tr>
		<td>{{$user->account_name}}</td>
		<td>{{$user->fname." ".$user->lname}}</td>
		<td>{{ date('Y-m-d', strtotime($user->date_activated)) }}</td>
		<td>{{$user->role_title}}</td>
		<td>
			@if($date1 < $date2 && $date1 > $date3) 
				1 
				@php $ctr_past_month_prorate++; @endphp
			@endif
		</td>
		<td>
			@if($date1 < $date2 && $date1 > $date3) 
				${{ $past_month_prorate }} 
				@php $total_past_month_prorate+=$past_month_prorate; @endphp
			@endif 
		</td>
		<td align="center">
			@if($date1 <= $date2) 
				1 
				@php $ctr_curr_month_prorate++; @endphp
			@endif
		</td>
		<td align="right">
			@if($date1 <= $date2) 
				${{$user->billing_rate}} 
				@php $total_curr_month_prorate+=$user->billing_rate; @endphp
			@endif
		</td>
		<td>
			@if($date1 < $date2 && $date1 > $date3) 
			@else
				@php $past_month_prorate = 0; @endphp
			@endif
			@if($date1 <= $date2)
				${{$past_month_prorate+$user->billing_rate}}
			@endif
		</td>
	</tr>
	@php 
		$account_holder = $user->account_name; 
		$ctr++;
	@endphp

	@if ($loop->last) 
	<tr class="bg-blue">
		<td></td>
		<td>Total</td>
		<td></td>
		<td></td>
		<td>{{$ctr_past_month_prorate}}</td>
		<td>${{$total_past_month_prorate}}</td>
		<td align="center">{{$ctr_curr_month_prorate}}</td>
		<td align="right">${{ number_format($total_curr_month_prorate, 2, '.', '') }}</td>
		<td>${{$total_past_month_prorate+$total_curr_month_prorate}} </td>
	</tr>

	@endif
	@empty
	<tr><td colspan="3">No data found.</td></tr>
	@endforelse
</table>
