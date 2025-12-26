<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;

class ResultController extends Controller
{
    /**
     * GET /api/results
     * Returns active results ordered by `order` asc.
     */
    public function index()
    {
        try {
            $results = Result::where('status', 1)
                ->orderBy('order', 'asc')
                ->select('id', 'name', 'name_en', 'order')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Result list retrieved successfully',
                'data' => $results,
                'count' => $results->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving results',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
