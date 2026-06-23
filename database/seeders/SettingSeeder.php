<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'PT. Equity World Futures Surabaya',
                'office_ip' => null,
                'office_latitude' => null,
                'office_longitude' => null,
                'allowed_radius' => null,
                'check_in_start' => '07:00:00',
                'check_in_end' => '07:59:59',
                'check_out_start' => '17:00:00',
                'check_out_end' => '19:59:59',
            ]
        );
    }
}