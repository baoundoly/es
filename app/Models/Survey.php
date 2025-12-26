<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'voter_info_id',
        'voter_no',
        'apartment',
        'flat_no',
        'contact',
        'email',
        'survey_time',
        'result_id',
        'is_given_voterslip',
        'new_address',
        'extra_info',
            'latitude',
            'longitude',
            'created_by',
            'updated_by',
    ];

    public function voterInfo()
    {
        return $this->belongsTo(VoterInfo::class, 'voter_info_id');
    }

    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
