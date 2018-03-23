<table class="table text-center">
	<thead>
		<tr>
			<th>Form Name</th>
			<th>Status</th>
			<th>Total Leads</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	@if(count($result_array)!=0)
	@foreach($result_array as $data)
	<tr>
		<td>{{$data->name}}</td>
		<td><p @if($data->status=='ACTIVE') class="active-green" @else class="inactive-grey" @endif>{{$data->status}}</p></td>
		<td>{{$data->totalLead}}</td>
		@if($data->directoryid != '')
			<td id="{{$data->directoryid}}" class='viewList' style="cursor:pointer;">View List</td>
		@else
			<td id="{{$data->id}}" class='directoryModal' style="cursor:pointer;">Create List</td>
		@endif
	</tr>
	@endforeach
	@else
	<tr>
	<td colspan="4">No Lead Form Attatch to this page..</td>
	</tr>
	@endif
	</tbody>
</table>