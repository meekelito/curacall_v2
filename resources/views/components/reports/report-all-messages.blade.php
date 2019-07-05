<table class="table scrollable ">
  <tr class="bg-primary">
    @if(isset($repeat))
        <td>Full Name</td> 
    @endif
    <td>Case Link</td>
    <td>Call Received On</td>
    <td>Date Time Delivered</td>
    <td>Message Read By</td>
    <td>Language</td>
    <td>Time of Call</td>
    <td>Full Message</td>
    <td>Call Type</td>
    <td>Call Subtype</td>
    <td>Reason Call</td>
    <td>Reason of the Cancellation</td>
    <td>Reason for Being Late</td>
    <td>How Late Will You Be To Your Shift</td>
    <td>Doesn't know How Late Will Be To Shift</td>
    <td>Name of the Hospital</td>
    <td>Emergency</td>
    <td>Typology</td>
    <td>Actions Taken</td>
    <td>Caller Type</td>
    <td>Caregiver</td>
    <td>Relation to PT</td>
    <td>Relation to Field Worker</td>
    <td>Caller First Name</td>
    <td>Caller Last Name</td>
    <td>Caller Email Address</td>
    <td>Caller Telephone</td>
    <td>Caller Position Interested In</td>
    <td>Company Name</td>
    <td>Patient First Name</td>
    <td>Patient Last Name</td>
    <td>Patient Telephone</td>
    <td>Referral</td>
    <td>First Visit</td>
    <td>FieldWorker First Name</td>
    <td>FieldWorker Last Name</td>
    <td>Shift Start</td>
    <td>Shift End</td>
    <td>Services Requested</td>
  </tr>
  @forelse($cases as $case)
  <tr>
    @if(isset($repeat))
        <td>Full Name</td> 
    @endif
    <td>CRM Link</td>
    <td>{{ $case->created_on }}</td>
    <td>{{ $case->created_at }}</td>
    <td>{{ $case->read_by }}</td>
    <td>{{ $case->call_language }}</td>
    <td>{{ $case->time_of_call }}</td>
    <td>{{ $case->full_message }}</td>
    <td>{{ $case->call_type }}</td>
    <td>{{ $case->subcall_type }}</td>
    <td>{{ $case->call_reason }}</td>
    <td>{{ $case->reason_of_the_cancelation }}</td>
    <td>{{ $case->reason_for_being_late }}</td>
    <td>{{ $case->how_late_will_you_be_to_your_shift }}</td>
    <td>{{ $case->doesnt_know_how_late_will_be_to_shift }}</td>
    <td>{{ $case->name_of_the_hospital }}</td>
    <td>{{ $case->medical_emergency }}</td>
    <td>{{ $case->call_typology }}</td> 
    <td>{{ $case->actions_taken }}</td> 
    <td>{{ $case->caller_type }}</td>
    <td>{{ $case->caregiver_type }}</td>
    <td>{{ $case->relation_to_patient }}</td>
    <td>{{ $case->caller_relationship_with_field_worker}}</td>
    <td>{{ $case->caller_first_name }}</td>
    <td>{{ $case->caller_last_name }}</td>
    <td>{{ $case->caller_email_address }}</td>
    <td>{{ $case->caller_telephone_number }}</td>
    <td>{{ $case->caller_position_interested_in }}</td>
    <td>{{ $case->caller_calling_from }}</td>
    <td>{{ $case->patient_first_name}}</td>
    <td>{{ $case->patient_last_name }}</td>
    <td>{{ $case->patient_telephone_number }}</td>
    <td>{{ $case->new_referral_or_previous_patient}}</td>
    <td>{{ $case->patient_date_and_time_of_first_visit }}</td>
    <td>{{ $case->caregiver_first_name }}</td>
    <td>{{ $case->caregiver_last_name }}</td>
    <td>{{ $case->employee_date_time_of_shift_start }}</td>
    <td>{{ $case->employee_date_time_of_shift_end }}</td>
    <td>{{ $case->services_requested }}</td>
  </tr>
  @empty
  <tr><td colspan="38">No data found.</td></tr>
  @endforelse
</table>