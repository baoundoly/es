@extends('admin.layouts.app')
@section('content')

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header text-right">  
						<h4 class="card-title">Edit Voter Survey</h4>
						<a href="{{route('admin.survey-management.survey-info.list') }}" class="btn btn-sm btn-info"><i class="fas fa-list"></i> Survey List</a>
					</div>
					<div class="card-body">
						<form action="{{ route('admin.survey-management.survey-info.update', $editData->id) }}" method="POST" enctype="multipart/form-data">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="voter_info_id">Voter Info</label>
										<select name="voter_info_id" id="voter_info_id" class="form-control" required>
											<option value="">-- Select Voter --</option>
											@foreach($voters as $v)
												<option value="{{ $v->id }}" {{ $editData->voter_info_id == $v->id ? 'selected' : '' }}>{{ $v->name }} (Voter No: {{ $v->voter_no }})</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="voter_no">Voter No</label>
										<input type="text" name="voter_no" id="voter_no" class="form-control" value="{{ old('voter_no', $editData->voter_no) }}" readonly>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="apartment">Apartment</label>
										<input type="text" name="apartment" id="apartment" class="form-control" value="{{ old('apartment', $editData->apartment) }}" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="flat_no">Flat No</label>
										<input type="text" name="flat_no" id="flat_no" class="form-control" value="{{ old('flat_no', $editData->flat_no) }}" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="contact">Contact</label>
											<input type="text" name="contact" id="contact" class="form-control" value="{{ old('contact', $editData->contact) }}" required>
									</div>
								</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="email">Email</label>
											<input type="email" name="email" id="email" class="form-control" value="{{ old('email', $editData->email) }}" placeholder="user@example.com">
										</div>
									</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="result_id">Result</label>
										<select name="result_id" id="result_id" class="form-control" required>
											<option value="">-- Select Result --</option>
											@foreach(App\Models\Result::where('status',1)->orderBy('order','asc')->pluck('name','id') as $rid => $rname)
												<option value="{{ $rid }}" {{ $editData->result_id == $rid ? 'selected' : '' }}>{{ $rname }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="new_address">New Address</label>
										<input type="text" name="new_address" id="new_address" class="form-control" value="{{ old('new_address', $editData->new_address) }}" required>   
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="latitude">Latitude</label>
										<input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $editData->latitude) }}" placeholder="e.g. 23.777" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="longitude">Longitude</label>
										<input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', $editData->longitude) }}" placeholder="e.g. 90.399" />
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="extra_info">Extra Info</label>
										<textarea name="extra_info" id="extra_info" class="form-control" rows="4">{{ old('extra_info', $editData->extra_info) }}</textarea>
									</div>
								</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="survey_time">Survey Time</label>
											<input type="time" name="survey_time" id="survey_time" class="form-control" value="{{ old('survey_time', $editData->survey_time) }}">
										</div>
									</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
