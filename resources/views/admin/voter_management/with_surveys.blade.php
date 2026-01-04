@extends('admin.layouts.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="mb-0">Voters With Surveys</h4>
      <small class="text-muted">Showing voters who have submitted at least one survey</small>
    </div>
    <form method="get" class="form-inline">
      <select name="ward_no" class="form-control me-2">
        <option value="">All Wards</option>
        @foreach($ward_nos as $id => $name)
          <option value="{{ $id }}" {{ request('ward_no') == $id ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary">Filter</button>
    </form>
  </div>
  <div class="card-body">
    <style>
      .list-card{border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.06)}
      .v-name{font-weight:600}
      .v-sub{font-size:.85rem;color:#6c757d}
      .v-badge{background:#0b6b3a;color:#fff;padding:6px 8px;border-radius:6px;font-weight:600}
    </style>

    <div class="row g-3">
      @foreach($voters as $voter)
      <div class="col-12">
        <div class="p-3 list-card d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <div class="me-3" style="width:56px;height:56px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0b6b3a">{{ strtoupper(substr($voter->name,0,1)) }}</div>
            <div>
              <div class="v-name">{{ $voter->name }} <small class="text-muted">({{ $voter->voter_no }})</small></div>
              <div class="v-sub">Ward: {{ optional($voter->ward)->name }} &middot; Address: {{ $voter->address }}</div>
            </div>
          </div>
          <div class="text-end">
            <div class="mb-2"><span class="v-badge">Surveys: {{ $voter->surveys_count }}</span></div>
            <div>
              <a href="{{ route('admin.voter-management.voter-info.surveys', $voter->id) }}" class="btn btn-sm btn-outline-primary">View Surveys</a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-3">{{ $voters->links() }}</div>
  </div>
</div>
@endsection
