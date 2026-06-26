<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(
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

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'PT. Equity World Futures Surabaya',
                'check_in_start' => '07:00:00',
                'check_in_end' => '07:59:59',
                'check_out_start' => '17:00:00',
                'check_out_end' => '19:59:59',
            ]
        );

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'office_ip' => ['nullable', 'string', 'max:255'],
            'office_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'office_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'allowed_radius' => ['nullable', 'integer', 'min:1', 'max:10000'],

            'check_in_start' => ['required', 'date_format:H:i'],
            'check_in_end' => ['required', 'date_format:H:i', 'after_or_equal:check_in_start'],
            'check_out_start' => ['required', 'date_format:H:i'],
            'check_out_end' => ['required', 'date_format:H:i', 'after_or_equal:check_out_start'],
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'office_ip.max' => 'IP kantor maksimal 255 karakter.',

            'office_latitude.numeric' => 'Latitude kantor harus berupa angka.',
            'office_latitude.between' => 'Latitude harus berada antara -90 sampai 90.',
            'office_longitude.numeric' => 'Longitude kantor harus berupa angka.',
            'office_longitude.between' => 'Longitude harus berada antara -180 sampai 180.',

            'allowed_radius.integer' => 'Radius harus berupa angka bulat.',
            'allowed_radius.min' => 'Radius minimal 1 meter.',
            'allowed_radius.max' => 'Radius maksimal 10000 meter.',

            'check_in_start.required' => 'Jam mulai check-in wajib diisi.',
            'check_in_end.required' => 'Batas check-in normal wajib diisi.',
            'check_out_start.required' => 'Jam mulai check-out wajib diisi.',
            'check_out_end.required' => 'Batas check-out wajib diisi.',

            'check_in_start.date_format' => 'Format jam mulai check-in tidak valid.',
            'check_in_end.date_format' => 'Format batas check-in tidak valid.',
            'check_out_start.date_format' => 'Format jam mulai check-out tidak valid.',
            'check_out_end.date_format' => 'Format batas check-out tidak valid.',

            'check_in_end.after_or_equal' => 'Batas check-in harus setelah atau sama dengan jam mulai check-in.',
            'check_out_end.after_or_equal' => 'Batas check-out harus setelah atau sama dengan jam mulai check-out.',
        ]);

        $setting->update([
            'company_name' => $validated['company_name'],
            'office_ip' => $validated['office_ip'] ?? null,
            'office_latitude' => $validated['office_latitude'] ?? null,
            'office_longitude' => $validated['office_longitude'] ?? null,
            'allowed_radius' => $validated['allowed_radius'] ?? null,
            'check_in_start' => $validated['check_in_start'] . ':00',
            'check_in_end' => $validated['check_in_end'] . ':00',
            'check_out_start' => $validated['check_out_start'] . ':00',
            'check_out_end' => $validated['check_out_end'] . ':00',
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function resetDefault()
    {
        $defaults = [
            'office_ip' => '127.0.0.1',
            'check_in_start' => '07:00',
            'check_in_end' => '08:00',
            'check_out_start' => '17:00',
            'check_out_end' => '20:00',
            'office_latitude' => '-7.291958',
            'office_longitude' => '112.758690',
            'allowed_radius' => '100',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem berhasil dikembalikan ke default.');
    }
}