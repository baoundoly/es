@extends('admin.layouts.app')
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<!-- Filter Card -->
				<div class="card">
					<div class="card-header justify-content-between text-right">
                        <h4 class="card-title">Voters Survey List</h4>
                    <div>
                        <a href="{{ route('admin.survey-management.survey-info.export') }}" class="btn btn-sm btn-success float-end me-2"><i class="fas fa-file-excel"></i> Download Excel</a>
                        <a href="{{ route('admin.survey-management.survey-info.add') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Add Survey</a>
                    </div>
					</div>
					<div class="card-body">
                        {{-- table to show survey list --}}
                        @if($surveys->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Voter Name</th>
                                            <th>Voter No</th>
                                            <th>Voter Address</th>
                                            <th>Apartment</th>
                                            <th>Flat</th>
                                            <th>Contact</th>
                                            <th>Result</th>
                                            <th>New Address</th>
                                            <th>Extra Info</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Survey data will be populated here --}}
                                        @foreach($surveys as $index => $survey)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $survey->voterInfo->name ?? 'N/A' }}</td>
                                            <td>{{ $survey->voter_no ?? 'N/A' }}</td>
                                            <td>{{ $survey->voterInfo->address ?? 'N/A' }}</td>
                                            <td>{{ $survey->apartment }}</td>
                                            <td>{{ $survey->flat_no }}</td>
                                            <td>{{ $survey->contact }}</td>
                                            @php
                                                $resultName = $survey->result->name ?? '';
                                                $lower = strtolower($resultName);
                                                if ($lower === '' || strpos($lower, 'na') !== false || strpos($lower, 'not') !== false) {
                                                    $badgeClass = 'badge-secondary';
                                                } elseif (strpos($lower, 'yes') !== false || strpos($lower, 'support') !== false || strpos($lower, 'present') !== false) {
                                                    $badgeClass = 'badge-success';
                                                } elseif (strpos($lower, 'no') !== false || strpos($lower, 'oppose') !== false || strpos($lower, 'against') !== false) {
                                                    $badgeClass = 'badge-danger';
                                                } elseif (strpos($lower, 'maybe') !== false || strpos($lower, 'undecided') !== false) {
                                                    $badgeClass = 'badge-warning';
                                                } else {
                                                    $badgeClass = 'badge-primary';
                                                }
                                            @endphp
                                            <td><span class="badge {{ $badgeClass }}">{{ $survey->result->name ?? 'N/A' }}</span></td>
                                            
                                            <td>{{ $survey->new_address }}</td>
                                            <td>{{ $survey->extra_info }}</td>
                                            <td>
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#surveyModal{{ $survey->id }}">Show details</button>
                                            </td>

                                            <!-- Survey Details Modal (modern card-style) -->
                                            <div class="modal fade" id="surveyModal{{ $survey->id }}" tabindex="-1" role="dialog" aria-labelledby="surveyModalLabel{{ $survey->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content border-0 shadow-sm">
                                                        <div class="modal-header bg-primary text-white py-3">
                                                            <div>
                                                                <h5 class="modal-title mb-0" id="surveyModalLabel{{ $survey->id }}">{{ $survey->voterInfo->name ?? 'N/A' }}</h5>
                                                                <small class="text-light">Survey #{{ $survey->id }} • {{ $survey->voter_no ?? 'N/A' }}</small>
                                                            </div>
                                                            <div class="ml-auto text-right">
                                                                @if($survey->result)
                                                                    <span class="badge badge-light text-dark">{{ $survey->result->name ?? 'N/A' }}</span>
                                                                @endif
                                                                <button type="button" class="close text-white ml-3" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="row">
                                                                <div class="col-lg-7">
                                                                    <h6 class="mb-3 text-muted">Voter Details</h6>
                                                                    <dl class="row">
                                                                        <dt class="col-sm-4 text-muted">Name</dt>
                                                                        <dd class="col-sm-8">{{ $survey->voterInfo->name ?? 'N/A' }}</dd>

                                                                        <dt class="col-sm-4 text-muted">Voter No</dt>
                                                                        <dd class="col-sm-8">{{ $survey->voter_no ?? 'N/A' }}</dd>

                                                                        <dt class="col-sm-4 text-muted">Address</dt>
                                                                        <dd class="col-sm-8">{{ $survey->voterInfo->address ?? 'N/A' }}</dd>

                                                                        <dt class="col-sm-4 text-muted">New Address</dt>
                                                                        <dd class="col-sm-8">{{ $survey->new_address ?? 'N/A' }}</dd>

                                                                        <dt class="col-sm-4 text-muted">Contact</dt>
                                                                        <dd class="col-sm-8">{{ $survey->contact ?? 'N/A' }}</dd>

                                                                        <dt class="col-sm-4 text-muted">Apartment / Flat</dt>
                                                                        <dd class="col-sm-8">{{ $survey->apartment ?? '-' }} / {{ $survey->flat_no ?? '-' }}</dd>
                                                                    </dl>
                                                                </div>
                                                                <div class="col-lg-5">
                                                                    <h6 class="mb-3 text-muted">Survey Info</h6>
                                                                    <ul class="list-unstyled">
                                                                        <li><strong>Email:</strong> <span class="text-muted">{{ $survey->email ?? 'N/A' }}</span></li>
                                                                    <li><strong>Survey Time:</strong> <span class="text-muted">
                                                                        @php
                                                                            if ($survey->survey_time === null) {
                                                                                    echo 'N/A';
                                                                            } else {
                                                                                    $s = (int) $survey->survey_time;
                                                                                    $h = intdiv($s, 3600);
                                                                                    $m = intdiv($s % 3600, 60);
                                                                                    $sec = $s % 60;
                                                                                    echo sprintf('%dh %02dm %02ds', $h, $m, $sec);
                                                                            }
                                                                        @endphp
                                                                    </span></li>
                                                                        <li><strong>Geolocation:</strong> <span class="text-muted">{{ $survey->latitude ?? 'N/A' }}, {{ $survey->longitude ?? 'N/A' }}</span></li>
                                                                    <li><strong>Given Voter Slip:</strong> <span class="text-muted">
                                                                        @php
                                                                            $gv = $survey->is_given_voterslip;
                                                                            if ($gv === null) {
                                                                                    echo 'N/A';
                                                                            } else {
                                                                                    $gv = (int) $gv;
                                                                                    if ($gv === 1) echo 'Yes';
                                                                                    elseif ($gv === 2) echo 'No';
                                                                                    else echo 'NA';
                                                                            }
                                                                        @endphp
                                                                    </span></li>
                                                                        <li><strong>Created By:</strong> <span class="text-muted">{{ $survey->createdBy->name ?? 'System' }} @if($survey->created_at) <small class="text-muted">• {{ $survey->created_at->format('Y-m-d H:i') }}</small> @endif</span></li>
                                                                    </ul>
                                                                    @if($survey->result)
                                                                        <div class="mt-3">
                                                                            <small class="text-muted">Result (EN)</small>
                                                                            <div class="p-2 border rounded bg-light mt-1">{{ $survey->result->name_en ?? $survey->result->name ?? 'N/A' }}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <hr />
                                                            <h6 class="text-muted">Extra Information</h6>
                                                            <div class="p-3 bg-white border rounded" style="white-space:pre-wrap;">{{ $survey->extra_info ?? '—' }}</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
							<div class="d-flex justify-content-between align-items-center mt-4">
								<div>
									Showing {{ $surveys->firstItem() }} to {{ $surveys->lastItem() }} of {{ $surveys->total() }} results
								</div>
								<nav>
									{{ $surveys->render() }}
								</nav>
							</div>
						@else
							<div class="alert alert-info">
								<i class="fas fa-info-circle"></i> No voter records found. Try adjusting your filters.
							</div>
						@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection