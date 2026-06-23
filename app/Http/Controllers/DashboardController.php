<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $totalDivisions = Division::count();

        $today = now('Asia/Jakarta')->toDateString();

        $todayAttendances = Attendance::whereDate('attendance_date', $today)->count();

        $notYetAttendances = $totalEmployees - $todayAttendances;

        $rejectedAttendances = Attendance::where('verification_status', 'ditolak')->count();

        if ($notYetAttendances < 0) {
            $notYetAttendances = 0;
        }

        $recentAttendances = Attendance::with(['user.division', 'user.employee', 'rejectedBy'])
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalDivisions',
            'todayAttendances',
            'notYetAttendances',
            'rejectedAttendances',
            'recentAttendances'
        ));
    }

    public function leader()
    {
        return view('leader.dashboard');
    }

    public function employee()
    {
        $user = auth()->user();

        $today = now('Asia/Jakarta')->toDateString();

        $todayAttendance = Attendance::with('rejectedBy')
            ->where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $recentAttendances = Attendance::with('rejectedBy')
            ->where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        if (!$todayAttendance) {
            $attendanceStatus = 'Belum Check-in';
        } elseif (($todayAttendance->verification_status ?? 'valid') === 'ditolak') {
            $attendanceStatus = 'Ditolak';
        } elseif ($todayAttendance->status === 'terlambat') {
            $attendanceStatus = 'Terlambat';
        } elseif ($todayAttendance->check_out_time) {
            $attendanceStatus = 'Selesai';
        } elseif ($todayAttendance->check_in_time) {
            $attendanceStatus = 'Belum Check-out';
        } else {
            $attendanceStatus = 'Belum Check-in';
        }

        return view('employee.dashboard', compact(
            'user',
            'todayAttendance',
            'recentAttendances',
            'attendanceStatus'
        ));
    }
}