@extends('admin.layouts.app')
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header text-right">  
						<h4 class="card-title">Voter Info Add</h4>
						<a href="{{route('admin.voter-management.voter-info.list') }}" class="btn btn-sm btn-info"><i class="fas fa-list"></i> Voter List</a>
					</div>
					<div class="card-body">
                        <form action="{{ route('admin.voter-management.voter-info.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ward_no">Ward No <span class="text-danger">*</span></label>
                                        <select name="ward_no" class="form-control" required>
                                            <option value="" disabled selected>Select Ward No</option>
                                            @foreach($ward_nos as $id => $name)
                                                <option value="{{ $id }}" {{ old('ward_no') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_no">File No <span class="text-danger">*</span></label>
                                        <input type="text" name="file_no" class="form-control" placeholder="Enter File No" value="{{ old('file_no') }}" required>
                                        @error('file_no')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Voter gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            {{-- <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option> --}}
                                        </select>
                                    </div>
                                </div> 
                                {{-- upload excel file --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="excel_file">Upload Excel File <span class="text-danger">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" required>
                                        @error('excel_file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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

<script>
    $(document).ready(function() {
        // 
    });
    
</script>
@endsection