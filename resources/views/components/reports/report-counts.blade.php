<table class="table" style="margin-top: 40px;">
  <tr class="bg-primary">
    <td>Staff Name</td>
    <td>Number</td>
  </tr>
  @forelse($cases as $case)
  <tr>
    <td>{{ $case->employee_first_name.' '.$case->employee_last_name }}</td>
    <td>{{ $case->total }}</td>
  </tr>
  @empty
  <tr><td colspan="2">No data found.</td></tr>
  @endforelse
  
</table>