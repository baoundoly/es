<?php

namespace App\Imports;

use App\Models\VoterInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;

class VoterImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    private $ward_no_id;
    private $file_no;
    private $gender;
    private $created_by;
    private $source_file;
    private $rowCount = 0;
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];

    public function __construct($ward_no_id, $file_no, $gender, $created_by = null, $source_file = null)
    {
        $this->ward_no_id = $ward_no_id;
        $this->file_no = $file_no;
        $this->gender = $gender;
        $this->created_by = $created_by;
        $this->source_file = $source_file;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Log the first row to see the actual column names
        if ($this->rowCount == 1) {
            Log::info("First row columns: " . json_encode(array_keys($row)));
        }

        // Get the value from column - Excel converts Bengali to ASCII slugs
        $serialNo = $this->getColumnValue($row, ['krmik_nng', 'ক্রমিক নং', 'ক্রমিক_নং', 'সিরিয়াল']);
        $name = $this->getColumnValue($row, ['nam', 'নাম', 'name', 'নাম ']);
        $voterNo = $this->getColumnValue($row, ['votar_nng', 'ভোটার নং', 'ভোটার_নং', 'voter_no', 'ভোটার নং ']);
        $fatherName = $this->getColumnValue($row, ['pitar_nam', 'পিতার নাম', 'পিতার_নাম', 'father_name', 'পিতার নাম ']);
        $motherName = $this->getColumnValue($row, ['matar_nam', 'মাতার নাম', 'মাতার_নাম', 'mother_name', 'মাতার নাম ']);
        $profession = $this->getColumnValue($row, ['pesa', 'পেশা', 'profession', 'পেশা ']);
        $dob = $this->getColumnValue($row, ['jnm_tarikh', 'জন্ম তারিখ', 'জন্ম_তারিখ', 'date_of_birth', 'জন্ম তারিখ ']);
        $address = $this->getColumnValue($row, ['thikana', 'ঠিকানা', 'address', 'ঠিকানা ']);

        // Skip empty rows
        if (empty($name) && empty($voterNo)) {
            if ($this->rowCount <= 5) {
                Log::info("Skipping empty row {$this->rowCount} - name: '{$name}', voter_no: '{$voterNo}'");
            }
            return null;
        }

        try {
            $voter = new VoterInfo([
                'serial_no' => $serialNo ?? $this->rowCount,
                'name' => $name,
                'voter_no' => $voterNo,
                'father_name' => $fatherName,
                'mother_name' => $motherName,
                'profession' => $profession,
                'date_of_birth' => $dob, // Store as-is without conversion
                'address' => $address,
                'ward_no_id' => $this->ward_no_id,
                'file_no' => $this->file_no,
                'gender' => $this->gender,
                'created_by' => $this->created_by,
                'source_file' => $this->source_file,
                'status' => 1,
            ]);
            
            $this->successCount++;
            
            // Log success for first few rows
            if ($this->rowCount <= 3) {
                Log::info("Row {$this->rowCount} imported successfully: {$name} - {$voterNo}");
            }
            
            return $voter;
        } catch (\Exception $e) {
            $this->errorCount++;
            $errorMsg = "Row {$this->rowCount}: " . $e->getMessage();
            $this->errors[] = $errorMsg;
            Log::error("Import error at row {$this->rowCount}: " . $e->getMessage() . " | Data: " . json_encode([
                'name' => $name,
                'voter_no' => $voterNo,
            ]));
            return null;
        }
    }

    /**
     * Get column value by trying multiple possible column names
     */
    private function getColumnValue(array $row, array $possibleNames)
    {
        foreach ($possibleNames as $name) {
            if (isset($row[$name])) {
                return $row[$name];
            }
        }
        return null;
    }

    public function rules(): array
    {
        return [
            // Required fields from Excel
            '*.nam' => 'required|string', // Name is required
            '*.votar_nng' => 'required|string|unique:voter_infos,voter_no', // Voter No is required and must be unique
            
            // Optional fields from Excel
            '*.krmik_nng' => 'nullable|integer',
            '*.pitar_nam' => 'nullable|string',
            '*.matar_nam' => 'nullable|string',
            '*.pesa' => 'nullable|string',
            '*.jnm_tarikh' => 'nullable|string',
            '*.thikana' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nam.required' => 'Name (নাম) is required in all rows',
            '*.votar_nng.required' => 'Voter No (ভোটার নং) is required in all rows',
            '*.votar_nng.unique' => 'Voter No (ভোটার নং) must be unique - duplicate found',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errorCount++;
        $this->errors[] = $e->getMessage();
        Log::error("Import error: " . $e->getMessage());
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Parse various date formats
     */
    private function parseDate($dateValue)
    {
        if (!$dateValue) {
            return null;
        }

        try {
            // Try to parse as Carbon date
            $date = Carbon::parse($dateValue);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                // Try parsing Bengali date format (DD/MM/YYYY)
                if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dateValue, $matches)) {
                    return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Return null if date cannot be parsed
                return null;
            }
        }

        return null;
    }
}
