<?php

namespace App\Http\Controllers\Admin\ResultManagement;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
	public function index()
	{
		$results = Result::orderBy('order', 'asc')->paginate(30);

		return view('admin.result_management.index', compact('results'));
	}

	public function add()
	{
		return view('admin.result_management.add');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'order' => 'nullable|integer|min:0',
			'status' => 'required|in:0,1',
		]);

		Result::create($validated);

		return redirect()
			->route('admin.result-management.result-info.list')
			->with('success', 'Result created successfully.');
	}

	public function edit(Result $editData)
	{
		return view('admin.result_management.edit', compact('editData'));
	}

	public function update(Request $request, Result $editData)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'order' => 'nullable|integer|min:0',
			'status' => 'required|in:0,1',
		]);

		$editData->update($validated);

		return redirect()
			->route('admin.result-management.result-info.list')
			->with('success', 'Result updated successfully.');
	}

	public function destroy(Request $request)
	{
		$request->validate([
			'id' => 'required|exists:results,id',
		]);

		$result = Result::findOrFail($request->id);
		$result->delete();

		return redirect()
			->route('admin.result-management.result-info.list')
			->with('success', 'Result deleted successfully.');
	}
}
