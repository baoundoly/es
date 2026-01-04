@extends('admin.layouts.app')

@section('content')
  <div class="card">
  <div class="card-header text-white" style="background-color:#0b6b3a;border-top-left-radius:8px;border-top-right-radius:8px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-0">Surveys for: {{ $voter->name }} <small class="text-white-50">({{ $voter->voter_no }})</small></h4>
        <p class="mb-0"><small class="text-white-50">Ward: {{ optional($voter->ward)->name }} &middot; Address: {{ $voter->address }}</small></p>
      </div>
      <div>
        <a href="{{ route('admin.voter-management.voter-info.with-surveys') }}" class="btn btn-light">Back to list</a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <style>
      .sv-avatar{width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#0b6b3a;color:#fff;font-weight:600;margin-right:10px}
      .sv-taker{display:flex;align-items:center}
      .sv-meta{font-size:.85rem;color:#6c757d}
    </style>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:72px">#</th>
            <th>Result</th>
            <th>Taken By</th>
            <th>New Address</th>
            <th>Contact</th>
            <th>Survey Time</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          @foreach($surveys as $survey)
          <tr>
            <td><strong>{{ $survey->id }}</strong></td>
            <td>
              @if(optional($survey->result)->name)
                <span class="badge bg-primary">{{ $survey->result->name }}</span>
              @else
                <span class="badge bg-secondary">{{ $survey->result_id }}</span>
              @endif
            </td>
            <td>
              <div class="sv-taker">
                <div class="sv-avatar">{{ strtoupper(substr(optional($survey->createdBy)->name ?? optional($survey->createdBy)->email ?? 'U',0,1)) }}</div>
                <div>
                  <div>{{ optional($survey->createdBy)->name ?? optional($survey->createdBy)->email ?? $survey->created_by }}</div>
                  <div class="sv-meta">{{ optional($survey->createdBy)->email }}</div>
                </div>
              </div>
            </td>
            <td>{{ $survey->new_address }}</td>
            <td>{{ $survey->contact }}</td>
            <td>
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
            </td>
            <td>
              <div>{{ optional($survey->created_at)->format('Y-m-d H:i') }}</div>
              <div class="sv-meta">{{ optional($survey->created_at)->diffForHumans() }}</div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
