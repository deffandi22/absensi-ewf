<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteDailyAttendancePhotos extends Command
{
    protected $signature = 'attendance:delete-daily-photos';

    protected $description = 'Menghapus foto absensi lama secara otomatis setiap hari.';

    public function handle(): int
    {
        $today = now()->toDateString();

        $attendances = Attendance::whereDate('attendance_date', '<', $today)
            ->where(function ($query) {
                $query->whereNotNull('check_in_photo')
                    ->orWhereNotNull('check_out_photo');
            })
            ->get();

        $deletedCount = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->check_in_photo && Storage::disk('public')->exists($attendance->check_in_photo)) {
                Storage::disk('public')->delete($attendance->check_in_photo);
                $deletedCount++;
            }

            if ($attendance->check_out_photo && Storage::disk('public')->exists($attendance->check_out_photo)) {
                Storage::disk('public')->delete($attendance->check_out_photo);
                $deletedCount++;
            }

            $attendance->update([
                'check_in_photo' => null,
                'check_out_photo' => null,
            ]);
        }

        $this->info("Berhasil menghapus {$deletedCount} foto absensi lama.");

        return Command::SUCCESS;
    }
}