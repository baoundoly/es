<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\VoterInfo;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Store a newly created survey in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voter_info_id' => 'required|exists:voter_infos,id',
            'voter_no' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'survey_time' => 'nullable|integer',
            'apartment' => 'nullable|string|max:255',
            'flat_no' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'result_id' => 'required|exists:results,id',
            'is_given_voterslip' => 'nullable|in:0,1,2',
            'new_address' => 'nullable|string|max:255',
            'extra_info' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            // set created_by from authenticated API user if available
            if (auth()->check()) {
                $validated['created_by'] = auth()->id();
            }
            $survey = Survey::create($validated);

            // load relations for response
            $survey->load(['voterInfo', 'result']);

            return response()->json([
                'success' => true,
                'message' => 'Survey created successfully.',
                'data' => $survey,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create survey.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all surveys with voter information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $surveys = Survey::with(['voterInfo', 'result'])->paginate(30);

        return response()->json([
            'success' => true,
            'data' => $surveys,
        ], 200);
    }

    /**
     * Get a single survey by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $survey = Survey::with(['voterInfo', 'result'])->find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $survey,
        ], 200);
    }

    /**
     * Update the specified survey in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found.',
            ], 404);
        }

        $validated = $request->validate([
            'voter_info_id' => 'required|exists:voter_infos,id',
            'voter_no' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'survey_time' => 'nullable|integer',
            'apartment' => 'nullable|string|max:255',
            'flat_no' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'result_id' => 'required|exists:results,id',
            'is_given_voterslip' => 'nullable|in:0,1,2',
            'new_address' => 'nullable|string|max:255',
            'extra_info' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            if (auth()->check()) {
                $validated['updated_by'] = auth()->id();
            }
            $survey->update($validated);

            // reload relations
            $survey->load(['voterInfo', 'result']);

            return response()->json([
                'success' => true,
                'message' => 'Survey updated successfully.',
                'data' => $survey,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update survey.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified survey from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found.',
            ], 404);
        }

        try {
            $survey->delete();

            return response()->json([
                'success' => true,
                'message' => 'Survey deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete survey.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
