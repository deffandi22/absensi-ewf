<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class LeaderDashboardController extends Controller
{
    public function index()
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $totalEmployees = User::where('role', 'employee')
            ->where('division_id', $divisionId)
            ->count();

        $attendanceToday = Attendance::whereDate('attendance_date', $today)
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'terlambat')
            ->where(function ($query) {
                $query->whereNull('verification_status')
                    ->orWhere('verification_status', '!=', 'ditolak');
            })
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->count();

        $notAttendToday = $totalEmployees - $attendanceToday;

        $rejectedAttendances = Attendance::where('verification_status', 'ditolak')
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->count();


        if ($notAttendToday < 0) {
            $notAttendToday = 0;
        }

        $recentAttendances = Attendance::with(['user.employee', 'user.division', 'rejectedBy'])
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('leader.dashboard', compact(
            'leader',
            'totalEmployees',
            'attendanceToday',
            'lateToday',
            'notAttendToday',
            'rejectedAttendances',
            'recentAttendances'
        ));
    }
}