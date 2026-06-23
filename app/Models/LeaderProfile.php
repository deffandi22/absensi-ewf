<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderProfile extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'phone',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}