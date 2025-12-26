@extends('admin.layouts.app')
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header justify-content-between text-right">
						<h4 class="card-title">Result List</h4>
						<a href="{{ route('admin.result-management.result-info.add') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-plus"></i> Add Result</a>
					</div>
					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success">{{ session('success') }}</div>
						@endif

						@if($results->count() > 0)
							<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th>Name</th>
											<th width="10%">Order</th>
											<th width="10%">Status</th>
											<th class="text-center" width="20%">Actions</th>
										</tr>
									</thead>
									<tbody>
										@foreach($results as $index => $result)
										<tr>
											<td>{{ $results->firstItem() + $index }}</td>
											<td>{{ $result->name }}</td>
											<td>{{ $result->order ?? 'N/A' }}</td>
											<td>
												<span class="badge {{ $result->status ? 'badge-success' : 'badge-secondary' }}">
													{{ $result->status ? 'Active' : 'Inactive' }}
												</span>
											</td>
											<td class="text-center">
												<a href="{{ route('admin.result-management.result-info.edit', $result->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
												<form action="{{ route('admin.result-management.result-info.destroy') }}" method="POST" class="d-inline">
													@csrf
													<input type="hidden" name="id" value="{{ $result->id }}">
													<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this result?');">
														<i class="fas fa-trash"></i> Delete
													</button>
												</form>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="d-flex justify-content-between align-items-center mt-4">
								<div>
									Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} results
								</div>
								<nav>
									{{ $results->render() }}
								</nav>
							</div>
						@else
							<div class="alert alert-info">
								<i class="fas fa-info-circle"></i> No result records found.
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
