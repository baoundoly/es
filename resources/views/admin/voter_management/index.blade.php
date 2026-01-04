@extends('admin.layouts.app')
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<!-- Filter Card -->
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Filter Voters</h4>
					</div>
					<div class="card-body">
						<form action="{{ route('admin.voter-management.voter-info.list') }}" method="GET" class="form-horizontal">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="ward_no">Ward No</label>
										<select name="ward_no" id="ward_no" class="form-control">
											<option value="">-- Select Ward No --</option>
											@foreach($ward_nos as $id => $name)
												<option value="{{ $id }}" {{ isset($filters['ward_no']) && $filters['ward_no'] == $id ? 'selected' : '' }}>
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
												<option value="{{ $no }}" {{ isset($filters['file_no']) && $filters['file_no'] == $no ? 'selected' : '' }}>
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
										<button type="submit" class="btn btn-primary btn-block">
											<i class="fas fa-search"></i> Filter
										</button>
										<a href="{{ route('admin.voter-management.voter-info.list') }}" class="btn btn-secondary btn-block mt-2">
											<i class="fas fa-redo"></i> Reset
										</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!-- Data Table Card -->
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							Voter List 
							@if($voters->total() > 0)
								<span class="badge badge-info">{{ $voters->total() }} Records</span>
							@endif
						</h4>
						<div>
							<a href="{{ route('admin.voter-management.voter-info.with-surveys') }}" class="btn btn-sm btn-primary">Voters with Surveys</a>
						</div>
						<a href="{{ route('admin.voter-management.voter-info.add') }}" class="btn btn-sm btn-success float-right">
							<i class="fas fa-plus"></i> Add New Voter
						</a>
					</div>
					<div class="card-body">
						@if($voters->count() > 0)
							<div class="table-responsive">
								<table class="table table-striped table-hover">
									<thead class="table-dark">
										<tr>
											<th width="5%">SL</th>
											<th>Name</th>
											<th>Voter No</th>
											<th>Father Name</th>
											<th>Mother Name</th>
											<th>Profession</th>
											<th>Address</th>
											{{-- <th width="10%">Action</th> --}}
										</tr>
									</thead>
									<tbody>
										@php $sl = ($voters->currentPage() - 1) * $voters->perPage() + 1; @endphp
										@foreach($voters as $voter)
											<tr>
												<td>{{ $sl++ }}</td>
												<td>{{ $voter->name }}</td>
												<td>{{ $voter->voter_no }}</td>
												<td>{{ $voter->father_name ?? '-' }}</td>
												<td>{{ $voter->mother_name ?? '-' }}</td>
												<td>{{ $voter->profession ?? '-' }}</td>
												<td>{{ $voter->address ?? '-' }}</td>
												{{-- <td>
													<a href="{{ route('admin.voter-management.voter-info.edit', $voter->id) }}" class="btn btn-xs btn-info">
														<i class="fas fa-edit"></i>
													</a>
													<button type="button" class="btn btn-xs btn-danger" onclick="confirmDelete({{ $voter->id }})">
														<i class="fas fa-trash"></i>
													</button>
												</td> --}}
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>

							<!-- Pagination -->
							<div class="d-flex justify-content-between align-items-center mt-4">
								<div>
									Showing {{ $voters->firstItem() }} to {{ $voters->lastItem() }} of {{ $voters->total() }} results
								</div>
								<nav>
									{{ $voters->render() }}
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

<script>
	function confirmDelete(voterId) {
		if (confirm('Are you sure you want to delete this voter record?')) {
			// You can implement the delete functionality here
			console.log('Delete voter: ' + voterId);
		}
	}
</script>
@endsection