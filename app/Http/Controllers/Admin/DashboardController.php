<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Survey;
use App\Models\WardNo;
use App\Models\VoterInfo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $distribution = Survey::selectRaw('result_id, COUNT(*) as total')
            ->groupBy('result_id')
            ->pluck('total', 'result_id');

        $resultNames = collect();
        if ($distribution->isNotEmpty()) {
            $ids = $distribution->keys()->filter(fn($id) => !is_null($id))->all();
            if (!empty($ids)) {
                $resultNames = Result::whereIn('id', $ids)->pluck('name', 'id');
            }
        }

        $labels = [];
        $counts = [];
        foreach ($distribution as $resultId => $count) {
            $labels[] = $resultId ? ($resultNames[$resultId] ?? 'Unknown') : 'Unknown';
            $counts[] = (int) $count;
        }

        // Stacked bar chart: Survey by Result and Gender
        $genderDistribution = Survey::join('voter_infos', 'surveys.voter_info_id', '=', 'voter_infos.id')
            ->groupBy('surveys.result_id', 'voter_infos.gender')
            ->selectRaw('surveys.result_id, voter_infos.gender, COUNT(*) as total')
            ->get();

        // Build result IDs list from gender distribution
        $resultIds = $genderDistribution->pluck('result_id')->unique()->filter(fn($id) => !is_null($id))->sort()->values()->all();
        
        $barResultNames = [];
        if (!empty($resultIds)) {
            $barResultNames = Result::whereIn('id', $resultIds)->pluck('name', 'id')->toArray();
        }

        // Structure data: each result gets male and female counts
        $barLabels = [];
        $maleData = [];
        $femaleData = [];
        
        foreach ($resultIds as $rid) {
            $barLabels[] = $barResultNames[$rid] ?? 'Unknown';
            
            $maleCount = 0;
            $femaleCount = 0;
            
            foreach ($genderDistribution as $record) {
                if ($record->result_id == $rid) {
                    if (strtolower(trim($record->gender)) === 'male') {
                        $maleCount = (int)$record->total;
                    } elseif (strtolower(trim($record->gender)) === 'female') {
                        $femaleCount = (int)$record->total;
                    }
                }
            }
            
            $maleData[] = $maleCount;
            $femaleData[] = $femaleCount;
        }

        // Additional summary stats for new dashboard
        $totalSurveys = Survey::count();
        $completedToday = Survey::whereDate('created_at', now()->toDateString())->count();
        $pendingVerification = Survey::whereNull('result_id')->count();
        $flaggedEntries = Survey::where('extra_info', 'like', '%flag%')->count();

            // Totals from other models
            $totalWards = WardNo::count();
            $totalVoters = VoterInfo::count();

            // Active enumerators (distinct creators with surveys in last 7 days)
            if (Schema::hasColumn('surveys', 'created_by')) {
                $activeEnumerators = Survey::whereNotNull('created_by')
                    ->where('created_at', '>=', now()->subDays(7)->startOfDay())
                    ->distinct('created_by')
                    ->count('created_by');
            } else {
                // Fallback: no created_by column — set to 0 and avoid query error
                $activeEnumerators = 0;
            }

        $completedCount = Survey::whereNotNull('result_id')->count();
        $completionRate = $totalSurveys ? round(($completedCount / $totalSurveys) * 100) . '%' : '0%';

        // Daily counts for last 7 days
        $daily = Survey::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $completionLabels = [];
        $completionData = [];
        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D');
            $completionLabels[] = $label;
            $completionData[] = (int) ($daily[$day] ?? 0);
            $dailyLabels[] = $label;
            $dailyData[] = (int) ($daily[$day] ?? 0);
        }

        $recentSurveys = Survey::with('voterInfo')->orderBy('created_at', 'desc')->limit(6)->get();
        $coverageCount = Survey::where('created_at', '>=', now()->subDays(7))->count();

        Log::debug('dashboard data', [
            'completionLabels' => $completionLabels,
            'completionData' => $completionData,
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'recentCount' => count($recentSurveys),
        ]);

        // Prepare variables expected by the view
        $resultLabels = $labels;
        $resultData = $counts;

        return view('admin.dashboard', compact(
            'resultLabels','resultData','barLabels', 'maleData', 'femaleData',
            'totalSurveys','completedToday','pendingVerification','flaggedEntries','completionRate',
            'completionLabels','completionData','dailyLabels','dailyData','recentSurveys','coverageCount',
            'totalWards','totalVoters','activeEnumerators'
        ));
    }
}
