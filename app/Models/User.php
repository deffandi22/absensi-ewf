<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\LeaderProfile;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'division_id',
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaderProfile()
    {
        return $this->hasOne(LeaderProfile::class);
    }
}