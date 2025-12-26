@extends('admin.layouts.app')
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-8 offset-lg-2">
				<div class="card">
					<div class="card-header text-right">
						<h4 class="card-title">Add Result</h4>
						<a href="{{ route('admin.result-management.result-info.list') }}" class="btn btn-sm btn-info"><i class="fas fa-list"></i> Result List</a>
					</div>
					<div class="card-body">
						@if($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form action="{{ route('admin.result-management.result-info.store') }}" method="POST">
							@csrf
							<div class="form-group mb-3">
								<label for="name">Name<span class="text-danger">*</span></label>
								<input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
							</div>
							<div class="form-group mb-3">
								<label for="order">Order</label>
								<input type="number" name="order" id="order" class="form-control" value="{{ old('order') }}" min="0" placeholder="Optional">
							</div>
							<div class="form-group mb-4">
								<label for="status">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
									<option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
								</select>
							</div>

							<button type="submit" class="btn btn-primary">Create</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
