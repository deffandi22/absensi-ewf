<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $lat1 = deg2rad((float) $lat1);
        $lon1 = deg2rad((float) $lon1);
        $lat2 = deg2rad((float) $lat2);
        $lon2 = deg2rad((float) $lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($lat1) * cos($lat2) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function validateOfficeRadius($userLatitude, $userLongitude)
    {
        $setting = Setting::first();

        if (!$setting) {
            return null;
        }

        $officeLatitude = $setting->office_latitude;
        $officeLongitude = $setting->office_longitude;
        $allowedRadius = $setting->allowed_radius;

        if (
            $officeLatitude === null ||
            $officeLongitude === null ||
            $allowedRadius === null ||
            $allowedRadius <= 0
        ) {
            return null;
        }

        if (!is_numeric($userLatitude) || !is_numeric($userLongitude)) {
            return 'Lokasi tidak valid. Silakan ambil lokasi ulang.';
        }

        $distance = $this->calculateDistance(
            $officeLatitude,
            $officeLongitude,
            $userLatitude,
            $userLongitude
        );

        if ($distance > $allowedRadius) {
            return 'Lokasi Anda berada di luar radius kantor. Jarak Anda sekitar ' . round($distance) . ' meter dari titik kantor. Batas radius yang diizinkan adalah ' . $allowedRadius . ' meter.';
        }

        return null;
    }

    public function checkInForm()
    {
        $user = auth()->user();

        $todayAttendance = $this->getTodayAttendance($user->id);

        if ($todayAttendance && $todayAttendance->check_in_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda sudah melakukan check-in hari ini.');
        }

        return view('employee.attendance.check-in', compact('user', 'todayAttendance'));
    }

    public function checkInStore(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ], [
            'photo.required' => 'Foto check-in wajib diambil.',
            'latitude.required' => 'Lokasi wajib diizinkan.',
            'longitude.required' => 'Lokasi wajib diizinkan.',
            'latitude.between' => 'Latitude tidak valid.',
            'longitude.between' => 'Longitude tidak valid.',
        ]);

        $user = auth()->user();

        $existingAttendance = $this->getTodayAttendance($user->id);

        if ($existingAttendance && $existingAttendance->check_in_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda sudah melakukan check-in hari ini.');
        }

        $now = $this->serverNow();
        $currentTime = $now->format('H:i:s');

        $setting = Setting::first();

        $checkInStart = $setting->check_in_start ?? '07:00:00';
        $checkInEnd = $setting->check_in_end ?? '07:59:59';

        if ($currentTime < $checkInStart) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Waktu check-in belum dibuka. Check-in dimulai pukul 07.00 WIB.');
        }

        $locationError = $this->validateOfficeRadius(
            $request->latitude,
            $request->longitude
        );

        if ($locationError) {
            return redirect()
                ->back()
                ->with('warning', $locationError);
        }

        $status = $currentTime > $checkInEnd ? 'terlambat' : 'hadir';

        $photoPath = $this->saveBase64Image($request->photo, $user->id, 'checkin');

        Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $now->toDateString(),
            'check_in_time' => $now->format('H:i:s'),
            'check_in_photo' => $photoPath,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'status' => $status,
        ]);

        return redirect('/employee/dashboard')
            ->with('success', 'Check-in berhasil disimpan.');
    }

    public function checkOutForm()
    {
        $user = auth()->user();

        $todayAttendance = $this->getTodayAttendance($user->id);

        if (!$todayAttendance || !$todayAttendance->check_in_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda harus melakukan check-in terlebih dahulu.');
        }

        if (($todayAttendance->verification_status ?? 'valid') === 'ditolak') {
            return redirect('/employee/dashboard')
                ->with('warning', 'Absensi Anda sudah ditolak oleh Ketua Ruangan, sehingga check-out tidak dapat dilakukan.');
        }

        if ($todayAttendance->check_out_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda sudah melakukan check-out hari ini.');
        }

        return view('employee.attendance.check-out', compact('user', 'todayAttendance'));
    }

    public function checkOutStore(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ], [
            'photo.required' => 'Foto check-out wajib diambil.',
            'latitude.required' => 'Lokasi wajib diizinkan.',
            'longitude.required' => 'Lokasi wajib diizinkan.',
            'latitude.between' => 'Latitude tidak valid.',
            'longitude.between' => 'Longitude tidak valid.',
        ]);

        $user = auth()->user();

        $todayAttendance = $this->getTodayAttendance($user->id);

        if (!$todayAttendance || !$todayAttendance->check_in_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda harus melakukan check-in terlebih dahulu.');
        }

        if (($todayAttendance->verification_status ?? 'valid') === 'ditolak') {
            return redirect('/employee/dashboard')
                ->with('warning', 'Absensi Anda sudah ditolak oleh Ketua Ruangan, sehingga check-out tidak dapat dilakukan.');
        }

        if ($todayAttendance->check_out_time) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Anda sudah melakukan check-out hari ini.');
        }

        $now = $this->serverNow();
        $currentTime = $now->format('H:i:s');

        $setting = Setting::first();

        $checkOutStart = $setting->check_out_start ?? '17:00:00';
        $checkOutEnd = $setting->check_out_end ?? '19:59:59';

        if ($currentTime < $checkOutStart) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Waktu check-out belum dibuka. Check-out dimulai pukul 17.00 WIB.');
        }

        if ($currentTime > $checkOutEnd) {
            return redirect('/employee/dashboard')
                ->with('warning', 'Waktu check-out sudah berakhir. Check-out hanya sampai pukul 19.59 WIB.');
        }

        $locationError = $this->validateOfficeRadius(
            $request->latitude,
            $request->longitude
        );

        if ($locationError) {
            return redirect()
                ->back()
                ->with('warning', $locationError);
        }

        $photoPath = $this->saveBase64Image($request->photo, $user->id, 'checkout');

        $todayAttendance->update([
            'check_out_time' => $now->format('H:i:s'),
            'check_out_photo' => $photoPath,
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'status' => $todayAttendance->status === 'terlambat' ? 'terlambat' : 'selesai',
        ]);

        return redirect('/employee/dashboard')
            ->with('success', 'Check-out berhasil disimpan.');
    }

    private function serverNow()
    {
        return now('Asia/Jakarta');
    }

    private function getTodayAttendance($userId)
    {
        $today = $this->serverNow()->toDateString();

        return Attendance::where('user_id', $userId)
            ->whereDate('attendance_date', $today)
            ->first();
    }

    private function saveBase64Image($base64Image, $userId, $type)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            abort(422, 'Format foto tidak valid.');
        }

        $extension = strtolower($matches[1]);

        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        if (!in_array($extension, ['jpg', 'png', 'webp'])) {
            abort(422, 'Format foto tidak didukung.');
        }

        $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            abort(422, 'Foto gagal diproses.');
        }

        if (strlen($imageData) > 2 * 1024 * 1024) {
            abort(422, 'Ukuran foto terlalu besar. Silakan ambil ulang foto.');
        }

        $folder = $type === 'checkin' ? 'attendance/checkin' : 'attendance/checkout';

        $fileName = $type . '_' . $userId . '_' . $this->serverNow()->format('Ymd_His') . '.' . $extension;

        $path = $folder . '/' . $fileName;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }
}