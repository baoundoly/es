@extends('admin.layouts.app')
@section('content')
<style>
	/* Minimal custom styles to approximate the modern dashboard mock */
		.dash-card { border-radius: 12px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); background:#fff }
		.stat-card { border-radius: 10px; padding: 18px; background:#fff; }
		.stat-value { font-size:28px; font-weight:700; }
		.stat-label { color:#6b7280; font-size:13px; }
		.kpi-card { position:relative; overflow:hidden; border-radius:10px; background:#fff }
		.kpi-card .kpi-accent { position:absolute; left:0; top:0; bottom:0; width:6px; }
		.kpi-card .kpi-accent.green { background:#10b981 }
		.kpi-card .kpi-accent.red { background:#ef4444 }
		.kpi-card .p-3 { padding:12px }
		.chart-fixed { height:260px; position:relative; }
		.chart-fixed canvas { width:100% !important; height:100% !important; }
	.small-muted { color:#9ca3af; font-size:13px }
	.map-placeholder { background:linear-gradient(180deg,#f8fafc,#ffffff); border-radius:10px; height:240px; display:flex;align-items:center;justify-content:center;color:#9ca3af }
	.card-ghost { background:transparent; box-shadow:none }
</style>

<div class="container-fluid">
	<div class="row mb-3">
		@php
			$k_totalWards = $totalWards ?? 0;
			$k_totalVoters = $totalVoters ?? ($totalVotters ?? 0);
			$k_totalSurveys = $totalSurveys ?? 0;
			$k_completedToday = $completedToday ?? 0;
			$k_coverage = isset($coveragePercent) ? $coveragePercent : ($coverageCount ?? 0);
			$k_flagged = $flaggedEntries ?? 0;
			$k_activeEnumerators = $activeEnumerators ?? 0;
		@endphp

		{{-- Card 1: Total Wards / Total Voters --}}
		<div class="col-12 col-md-6 col-lg-3 mb-2">
			<div class="dash-card kpi-card">
				<div class="kpi-accent green"></div>
				<div class="p-3 d-flex justify-content-between align-items-center">
					<div>
						<div class="small-muted">Total Wards</div>
						<div class="stat-value">{{ number_format($k_totalWards) }}</div>
					</div>
					<div class="text-right">
						<div class="small-muted">Total Votters</div>
						<div class="stat-value">{{ number_format($k_totalVoters) }}</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Card 2: Total Surveys / Completed Today --}}
		<div class="col-12 col-md-6 col-lg-3 mb-2">
			<div class="dash-card kpi-card">
				<div class="kpi-accent green"></div>
				<div class="p-3 d-flex justify-content-between align-items-center">
					<div>
						<div class="small-muted">Total Surveys</div>
						<div class="stat-value">{{ number_format($k_totalSurveys) }}</div>
					</div>
					<div class="text-right">
						<div class="small-muted">Completed Today</div>
						<div class="stat-value text-success">{{ number_format($k_completedToday) }}</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Card 3: Coverage % --}}
		<div class="col-6 col-md-4 col-lg-2 mb-2">
			<div class="dash-card kpi-card">
				<div class="kpi-accent green"></div>
				<div class="p-3">
					<div class="small-muted">Coverage %</div>
					<div class="stat-value">{{ number_format($k_coverage, 2) }}%</div>
				</div>
			</div>
		</div>

		{{-- Card 4: Flagged Entries (red) --}}
		<div class="col-6 col-md-4 col-lg-2 mb-2">
			<div class="dash-card kpi-card">
				<div class="kpi-accent red"></div>
				<div class="p-3">
					<div class="small-muted">Flagged Entries</div>
					<div class="stat-value text-danger">{{ number_format($k_flagged) }}</div>
				</div>
			</div>
		</div>

		{{-- Card 5: Active Enumerators --}}
		<div class="col-6 col-md-4 col-lg-2 mb-2">
			<div class="dash-card kpi-card">
				<div class="kpi-accent green"></div>
				<div class="p-3">
					<div class="small-muted">Active Enumerators</div>
					<div class="stat-value">{{ number_format($k_activeEnumerators) }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-8">
			<div class="card dash-card mt-3">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<h5 class="mb-0">Survey Completion Rate</h5>
						<div class="text-right">
							<div id="completionTotal" class="badge badge-success">0</div>
							<div id="completionPct" class="small-muted" style="display:block;font-size:12px"></div>
						</div>
					</div>
					<div class="chart-fixed" style="height:160px"><canvas id="completionLine"></canvas></div>
				</div>
			</div>
			<div class="card dash-card mt-3">
				<div class="card-body">
					<h5 class="mb-3">Recent Survey Submissions</h5>
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Survey ID</th>
									<th>Enumerator</th>
									<th>Submission Time</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if(!empty($recentSurveys) && count($recentSurveys))
									@foreach($recentSurveys as $s)
										<tr>
											<td>{{ $s->id ?? '-' }}</td>
											<td>{{ $s->enumerator_name ?? ($s->created_by_name ?? 'N/A') }}</td>
											<td>{{ $s->created_at ? $s->created_at->format('d M Y, h:i A') : '-' }}</td>
											<td><span class="badge badge-success">Completed</span></td>
										</tr>
									@endforeach
								@else
									<tr><td colspan="4" class="text-center small-muted">No recent submissions</td></tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="card dash-card mt-3">
				<div class="card-body">
					<h5 class="mb-3">Results by Gender</h5>
					<div class="chart-fixed"><canvas id="resultBar"></canvas></div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card dash-card">
				<div class="card-body">
					<h5 class="mb-3">Geographic Coverage</h5>
					<div class="map-placeholder">Map placeholder (integrate Leaflet or other map)</div>
					<div class="mt-3 d-flex justify-content-between small-muted">
						<div>Survey Coverage</div>
						<div>{{ $coverageCount ?? 0 }} This Week</div>
					</div>
				</div>
			</div>

			<div class="card dash-card mt-3">
				<div class="card-body">
					<h5 class="mb-3">Result Distribution</h5>
					<canvas id="resultPie" style="height:160px"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- Charts: result-wise pie chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
	@php
		$__resultLabels = $resultLabels ?? ['Result A','Result B','Other'];
		$__resultData = $resultData ?? [55,30,15];
		$__barLabels = $barLabels ?? $__resultLabels;
		$__male = $maleData ?? [];
		$__female = $femaleData ?? [];
		$__completionLabels = $completionLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
		$__completionData = $completionData ?? [10,20,30,40,50,60,70];
	@endphp
	(function(){
		if (typeof Chart === 'undefined') {
			console.error('Chart.js is not loaded — check CDN or script order.');
			return;
		}

		try {
			const labels = @json($__resultLabels);
			const data = @json($__resultData);
			console.log('Result pie data', { labels, data });
			const ctx = document.getElementById('resultPie');
				if (ctx) {
					const pieCtx = ctx.getContext ? ctx.getContext('2d') : ctx;
					console.log('ResultPie data', { labels, data });
					new Chart(pieCtx, {
						type: 'pie',
						data: {
							labels: labels,
							datasets: [{ data: data, backgroundColor: ['#10b981','#60a5fa','#f97316','#ef4444'] }]
						},
						options: { responsive:true, plugins:{legend:{position:'bottom'}} }
					});
				}

				// Render stacked bar chart for results by gender
				try {
					const barLabels = @json($__barLabels);
					let maleSeries = @json($__male);
					let femaleSeries = @json($__female);
					console.log('ResultBar data', { barLabels, maleSeries, femaleSeries });
					// normalize lengths
					if (!Array.isArray(maleSeries)) maleSeries = [];
					if (!Array.isArray(femaleSeries)) femaleSeries = [];
					while (maleSeries.length < barLabels.length) maleSeries.push(0);
					while (femaleSeries.length < barLabels.length) femaleSeries.push(0);
					const ctxBar = document.getElementById('resultBar');
						if (ctxBar && Array.isArray(barLabels) && barLabels.length) {
							const barCtx = ctxBar.getContext ? ctxBar.getContext('2d') : ctxBar;
							new Chart(barCtx, {
							type: 'bar',
							data: {
								labels: barLabels,
								datasets: [
									{ label: 'Male', data: maleSeries, backgroundColor: '#60a5fa' },
									{ label: 'Female', data: femaleSeries, backgroundColor: '#f472b6' }
								]
							},
								options: {
									responsive: true,
									maintainAspectRatio: true,
									aspectRatio: 2,
									plugins: { legend: { position: 'bottom' } },
									scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
								}
						});
					}
				} catch (err) {
					console.error('Error rendering resultBar chart', err);
				}

				// Render completion line chart
				try {
					const cLabels = @json($__completionLabels);
					const cData = @json($__completionData);
					console.log('Completion line data', { cLabels, cData });
					const total = cData && cData.length ? cData[cData.length - 1] : 0;
					const prev = cData && cData.length > 1 ? cData[cData.length - 2] : 0;
					let pct = 0; let trend = '—';
					if (prev) { pct = Math.round(((total - prev) / Math.abs(prev)) * 100); trend = pct >= 0 ? '▲' : '▼'; }
						const totalEl = document.getElementById('completionTotal');
						const pctEl = document.getElementById('completionPct');
						if (totalEl) totalEl.innerText = total;
						if (pctEl) pctEl.innerText = prev ? (trend + ' ' + Math.abs(pct) + '% vs yesterday') : '';
						// Color: green for non-negative trend, red for negative
						const positive = (prev === 0 && total > 0) || (prev && pct >= 0);
						if (totalEl) totalEl.className = 'badge ' + (positive ? 'badge-success' : 'badge-danger');
					const lineEl = document.getElementById('completionLine');
						if (lineEl && Array.isArray(cLabels) && cLabels.length) {
							const lineCtx = lineEl.getContext ? lineEl.getContext('2d') : lineEl;
							// create gradient based on positive/negative trend
							const strokeColor = positive ? '#10b981' : '#ef4444';
							const grad = lineCtx.createLinearGradient(0,0,0,160);
							if (positive) {
								grad.addColorStop(0, 'rgba(16,185,129,0.12)');
								grad.addColorStop(1, 'rgba(16,185,129,0.02)');
							} else {
								grad.addColorStop(0, 'rgba(239,68,68,0.12)');
								grad.addColorStop(1, 'rgba(239,68,68,0.02)');
							}
							new Chart(lineCtx, {
								type: 'line',
								data: { labels: cLabels, datasets: [{ label: 'Surveys Completed', data: cData, borderColor: strokeColor, backgroundColor: grad, tension:0.3, fill:true, pointRadius:3 }] },
								options: { responsive:true, maintainAspectRatio:true, aspectRatio:3, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
							});
						}
				} catch (err) {
					console.error('Error rendering completionLine chart', err);
				}
		} catch (err) {
			console.error('Error rendering resultPie chart', err);
		}
	})();
</script>

@endsection
