<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_in_photo',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_time',
        'check_out_photo',
        'check_out_latitude',
        'check_out_longitude',
        'status',
        'verification_status',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}