<?php

namespace App\Http\Controllers\Admin\VoterManagement;

use App\Http\Controllers\Controller;
use App\Models\WardNo;
use App\Imports\VoterImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException;

use App\Models\VoterInfo;

class VoterController extends Controller
{
    public function index(Request $request)
    {
        $query = VoterInfo::query();
        
        if(!empty($request->ward_no) || !empty($request->file_no) || !empty($request->gender)) {
            // Apply filters if provided
            if ($request->filled('ward_no')) {
                $query->where('ward_no_id', $request->ward_no);
            }
            
            if ($request->filled('file_no')) {
                $query->where('file_no', $request->file_no);
            }
            
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }
            
            // Get paginated results (30 per page)
            $voters = $query->paginate(30)->appends($request->all());
        } else {
            $voters = $query->whereRaw('1 = 0')->paginate(30); // Empty collection if filters are not set
        }
        
        // Get filter options
        $data['ward_nos'] = WardNo::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id');
        $data['file_nos'] = VoterInfo::distinct()->pluck('file_no')->sort();
        $data['voters'] = $voters;
        $data['filters'] = [
            'ward_no' => $request->ward_no,
            'file_no' => $request->file_no,
            'gender' => $request->gender,
        ];
        
        return view('admin.voter_management.index', $data);
    }

    public function add()
    {
        $data['ward_nos'] = WardNo::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id');
        return view('admin.voter_management.add', $data);
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'ward_no' => 'required|exists:ward_nos,id',
            'file_no' => 'required|string',
            'gender' => 'required|in:male,female',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            // Get the uploaded file
            $file = $request->file('excel_file');
            
            // Store file name for reference
            $fileName = $file->getClientOriginalName();
            
            // Create import instance with form data
            $import = new VoterImport(
                $validated['ward_no'],
                $validated['file_no'],
                $validated['gender'],
                auth()->guard('admin')->id(),
                $fileName
            );

            // Import the file
            Excel::import($import, $file);

            // Get import statistics
            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();
            $failures = $import->failures();

            // Debug information
            $debugInfo = "Total rows processed: " . ($successCount + $errorCount) . " | Success: {$successCount} | Errors: {$errorCount}";
            \Log::info($debugInfo);

            // If no records were imported at all, check the logs
            if ($successCount == 0 && $errorCount == 0) {
                return redirect()->back()
                    ->with('error', 'No data was imported. Please check the Excel file format. Check storage/logs/laravel.log for details.')
                    ->withInput();
            }

            // Check if there were any failures
            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                }
                
                return redirect()->back()
                    ->with('warning', "Imported {$successCount} records. " . count($failures) . " rows failed. Errors: " . implode('; ', array_slice($errorMessages, 0, 5)))
                    ->withInput();
            }

            // Show errors if any
            if ($errorCount > 0) {
                $errorPreview = implode('; ', array_slice($errors, 0, 5));
                return redirect()->back()
                    ->with('warning', "Imported {$successCount} records. {$errorCount} rows had errors: {$errorPreview}. Check logs for details.")
                    ->withInput();
            }

            // All successful
            $message = "Successfully imported {$successCount} voter records!";

            return redirect()->route('admin.voter-management.voter-info.list')
                           ->with('success', $message);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                           ->with('error', 'Validation errors: ' . implode('; ', array_slice($errorMessages, 0, 10)))
                           ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error importing file: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function edit($editData)
    {
        return view('admin.voter_management.edit', compact('editData'));
    }

    public function update($editData)
    {
        // Logic to update voter information
    }

    public function destroy()
    {
        // Logic to delete voter information
    }
}