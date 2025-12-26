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
                        <a href="{{ route('admin.survey-management.survey-info.add') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Add Survey</a>
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
                                            {{-- <th>Actions</th> --}}
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
                                            <td>{{ $survey->result->name ?? 'N/A' }}</td>
                                            <td>{{ $survey->new_address }}</td>
                                            <td>{{ $survey->extra_info }}</td>
                                            {{-- <td>
                                                <a href="{{ route('admin.survey-management.survey-info.edit', $survey->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('admin.survey-management.survey-info.destroy') }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $survey->id }}">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this survey?');">Delete</button>
                                                </form>
                                            </td> --}}
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