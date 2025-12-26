@extends('admin.layouts.app')
@section('content')
<style>
    .voter-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 8px 16px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        z-index: 10;
    }
</style>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header text-right">  
						<h4 class="card-title">Voter Survey Add</h4>
						<a href="{{route('admin.survey-management.survey-info.list') }}" class="btn btn-sm btn-info"><i class="fas fa-list"></i> Survey List</a>
					</div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" name="latitude" id="latitude" class="form-control" placeholder="e.g. 23.777" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" name="longitude" id="longitude" class="form-control" placeholder="e.g. 90.399" />
                                    </div>
                                </div>
					<div class="card-body">
                        <div class="filter-result">
                            <div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="ward_no">Ward No</label>
										<select name="ward_no" id="ward_no" class="form-control">
											<option value="">-- Select Ward No --</option>
											@foreach($ward_nos as $id => $name)
												<option value="{{ $id }}">
													{{ $name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="file_no">File No</label>
										<select name="file_no" id="file_no" class="form-control">
											<option value="">-- Select File No --</option>
											@foreach($file_nos as $no)
												<option value="{{ $no }}">
													{{ $no }}
												</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="gender">Gender</label>
										<select name="gender" id="gender" class="form-control">
											<option value="">-- Select Gender --</option>
											<option value="male" {{ isset($filters['gender']) && $filters['gender'] == 'male' ? 'selected' : '' }}>Male</option>
											<option value="female" {{ isset($filters['gender']) && $filters['gender'] == 'female' ? 'selected' : '' }}>Female</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label>
										<button type="button" class="btn btn-primary btn-block" id="filter_submit">
											<i class="fas fa-search"></i> Filter
										</button>
									</div>
								</div>
							</div>
                        </div>
                        <form action="{{ route('admin.survey-management.survey-info.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Add your survey form fields here -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="voter_info_id">Voter Info</label>

                                        <div class="position-relative">
                                            <select name="voter_info_id" id="voter_info_id" class="form-control" required>
                                                <option value="">-- Select Voter --</option>
                                            </select>

                                            <!-- Loader -->
                                            <div id="voter-loader" class="voter-loader d-none">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <span class="ml-2">Loading voters...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="voter_no">Voter No</label>
                                        <input type="text" name="voter_no" id="voter_no" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="apartment">Apartment</label>
                                        <input type="text" name="apartment" id="apartment" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="flat_no">Flat No</label>
                                        <input type="text" name="flat_no" id="flat_no" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact">Contact</label>
                                        <input type="text" name="contact" id="contact" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="user@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="result_id">Result</label>
                                        <select name="result_id" id="result_id" class="form-control" required>
                                            <option value="">-- Select Result --</option>
                                            @foreach($results as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_address">New Address</label>
                                        <input type="text" name="new_address" id="new_address" class="form-control" required>   
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" name="latitude" id="latitude" class="form-control" placeholder="e.g. 23.777" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" name="longitude" id="longitude" class="form-control" placeholder="e.g. 90.399" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="extra_info">Extra Info</label>
                                        <textarea name="extra_info" id="extra_info" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="survey_time">Survey Time</label>
                                        <input type="time" name="survey_time" id="survey_time" class="form-control" value="{{ old('survey_time') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        function fetchVoterInfo() {
            var ward_no = $('#ward_no').val();
            var file_no = $('#file_no').val();
            var gender = $('#gender').val();

            $.ajax({
                url: '{{ route("admin.survey-management.survey-info.get-voter-info") }}',
                type: 'GET',
                data: {
                    ward_no: ward_no,
                    file_no: file_no,
                    gender: gender
                },
                beforeSend: function () {
                    $('#voter_info_id').prop('disabled', true);
                    $('#voter-loader').removeClass('d-none');
                },
                success: function (response) {
                    var voterSelect = $('#voter_info_id');
                    voterSelect.empty();
                    voterSelect.append('<option value="">-- Select Voter --</option>');

                    if (response.length === 0) {
                        voterSelect.append('<option value="" disabled>No voters found</option>');
                    }

                    $.each(response, function (index, voter) {
                        voterSelect.append(
                            '<option value="' + voter.id + '">' +
                            voter.name + ' (Voter No: ' + voter.voter_no + ')' +
                            '</option>'
                        );
                    });
                },
                complete: function () {
                    $('#voter-loader').addClass('d-none');
                    $('#voter_info_id').prop('disabled', false);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        $('#filter_submit').click(function (e) {
            e.preventDefault();
            fetchVoterInfo();
        });

        $('#voter_info_id').change(function () {
            var selectedOption = $(this).find('option:selected');
            var voterNoText = selectedOption.text();
            var voterNoMatch = voterNoText.match(/\(Voter No:\s*(.*?)\)/);

            if (voterNoMatch && voterNoMatch[1]) {
                $('#voter_no').val(voterNoMatch[1]);
            } else {
                $('#voter_no').val('');
            }
        });

    });
</script>

@endsection