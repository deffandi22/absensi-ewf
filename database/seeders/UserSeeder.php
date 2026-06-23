<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = Division::updateOrCreate(
            ['division_name' => 'Marketing'],
            [
                'description' => 'Divisi Marketing',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@ewf.com'],
            [
                'division_id' => null,
                'name' => 'Admin Absensi',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'leader@ewf.com'],
            [
                'division_id' => $marketing->id,
                'name' => 'Ketua Divisi Marketing',
                'password' => Hash::make('leader123'),
                'role' => 'leader',
            ]
        );

        $employee = User::updateOrCreate(
            ['email' => 'karyawan@ewf.com'],
            [
                'division_id' => $marketing->id,
                'name' => 'Dimas Karyawan',
                'password' => Hash::make('karyawan123'),
                'role' => 'employee',
            ]
        );

        Employee::updateOrCreate(
            ['user_id' => $employee->id],
            [
                'position' => 'Staff Marketing',
                'phone' => '081234567890',
                'address' => 'Surabaya',
            ]
        );
    }
}