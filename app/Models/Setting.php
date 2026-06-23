<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'office_ip',
        'office_latitude',
        'office_longitude',
        'allowed_radius',
        'check_in_start',
        'check_in_end',
        'check_out_start',
        'check_out_end',
    ];
}