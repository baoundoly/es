<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterInfo extends Model
{
    use HasFactory;

    protected $table = 'voter_infos';

    protected $fillable = [
        'serial_no',
        'name',
        'voter_no',
        'father_name',
        'mother_name',
        'profession',
        'date_of_birth',
        'address',
        'ward_no_id',
        'file_no',
        'gender',
        'created_by',
        'source_file',
        'status',
        'cant_access',
    ];

    public function ward()
    {
        return $this->belongsTo(WardNo::class, 'ward_no_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'voter_info_id');
    }
}
