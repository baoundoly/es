<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Return the collection of surveys with relationships
     */
    public function collection()
    {
        return Survey::with(['voterInfo', 'result', 'createdBy'])->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            '#',
            'Voter Name',
            'Voter No',
            'Voter Address',
            'Apartment',
            'Flat No',
            'Contact',
            'Email',
            'Result',
            'Result (EN)',
            'New Address',
            'Survey Time',
            'Given Voter Slip',
            'Latitude',
            'Longitude',
            'Extra Info',
            'Created By',
            'Created At',
        ];
    }

    /**
     * Map each survey to a row
     */
    public function map($survey): array
    {
        static $index = 0;
        $index++;

        // Format survey time
        $surveyTime = 'N/A';
        if ($survey->survey_time !== null) {
            $s = (int) $survey->survey_time;
            $h = intdiv($s, 3600);
            $m = intdiv($s % 3600, 60);
            $sec = $s % 60;
            $surveyTime = sprintf('%dh %02dm %02ds', $h, $m, $sec);
        }

        // Format voter slip
        $givenVoterSlip = 'N/A';
        if ($survey->is_given_voterslip !== null) {
            $gv = (int) $survey->is_given_voterslip;
            if ($gv === 1) {
                $givenVoterSlip = 'Yes';
            } elseif ($gv === 2) {
                $givenVoterSlip = 'No';
            } else {
                $givenVoterSlip = 'NA';
            }
        }

        return [
            $index,
            $survey->voterInfo->name ?? 'N/A',
            $survey->voter_no ?? 'N/A',
            $survey->voterInfo->address ?? 'N/A',
            $survey->apartment ?? '',
            $survey->flat_no ?? '',
            $survey->contact ?? '',
            $survey->email ?? '',
            $survey->result->name ?? 'N/A',
            $survey->result->name_en ?? ($survey->result->name ?? 'N/A'),
            $survey->new_address ?? '',
            $surveyTime,
            $givenVoterSlip,
            $survey->latitude ?? '',
            $survey->longitude ?? '',
            $survey->extra_info ?? '',
            $survey->createdBy->name ?? 'System',
            $survey->created_at ? $survey->created_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * Style the header row
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F4F8']
                ]
            ],
        ];
    }
}
