<?php

namespace App\Http\Controllers\Admin\SurveyManagement;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Survey;
use App\Models\VoterInfo;
use App\Models\WardNo;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $surveys = Survey::with(['voterInfo', 'result'])->paginate(30);
        
        return view('admin.survey_management.index', compact('surveys'));
    }

    public function add()
    {
        $data['ward_nos'] = WardNo::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id');
        $data['file_nos'] = VoterInfo::distinct()->pluck('file_no')->sort();
        $data['results'] = Result::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id');
        return view('admin.survey_management.add', $data);
    }

    public function getVoterInfo(Request $request)
    {
        $query = VoterInfo::query();

        if ($request->filled('ward_no')) {
            $query->where('ward_no_id', $request->ward_no);
        }

        if ($request->filled('file_no')) {
            $query->where('file_no', $request->file_no);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $voters = $query->get(['id', 'name', 'voter_no']);

        return response()->json($voters);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'voter_info_id' => 'required|exists:voter_infos,id',
            'voter_no' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'flat_no' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'survey_time' => 'nullable|date_format:H:i',
            'result_id' => 'required|exists:results,id',
            'is_given_voterslip' => 'nullable|in:0,1,2',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'new_address' => 'nullable|string|max:255',
            'extra_info' => 'nullable|string',
        ]);

        $data = $request->only([
            'voter_info_id', 'voter_no', 'apartment', 'flat_no', 'contact', 'email', 'survey_time', 'result_id', 'is_given_voterslip', 'latitude', 'longitude', 'new_address', 'extra_info'
        ]);
        // Record creator (admin) id
        if (auth()->check()) {
            $data['created_by'] = auth()->user()->id;
        }
        Survey::create($data);

        return redirect()->route('admin.survey-management.survey-info.list')->with('success', 'Survey created successfully.');
    }
    public function edit(Survey $editData)
    {
        $voters = VoterInfo::all();
        return view('admin.survey_management.edit', compact('editData', 'voters'));
    }
    public function update(Request $request, Survey $editData)
    {
        $request->validate([
            'voter_info_id' => 'required|exists:voter_infos,id',
            'voter_no' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'flat_no' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'survey_time' => 'nullable|date_format:H:i',
            'result_id' => 'required|exists:results,id',
            'is_given_voterslip' => 'nullable|in:0,1,2',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'extra_info' => 'nullable|string',
        ]);

        $updateData = $request->only([
            'voter_info_id', 'voter_no', 'apartment', 'flat_no', 'contact', 'email', 'survey_time', 'result_id', 'is_given_voterslip', 'latitude', 'longitude', 'extra_info'
        ]);
        if (auth()->check()) {
            $updateData['updated_by'] = auth()->user()->id;
        }
        $editData->update($updateData);

        return redirect()->route('admin.survey-management.survey-info.list')->with('success', 'Survey updated successfully.');
    }
    public function destroy(Request $request)
    {
        $survey = Survey::findOrFail($request->id);
        $survey->delete();

        return redirect()->route('admin.survey-management.survey-info.list')->with('success', 'Survey deleted successfully.');
    }
}