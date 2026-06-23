<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class LeaderReportController extends Controller
{
    public function index(Request $request)
    {
        $leader = auth()->user();

        $attendances = $this->getFilteredAttendanceQuery($request)
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('leader.reports.index', compact('attendances', 'leader'));
    }

    public function print(Request $request)
    {
        $leader = auth()->user();

        $attendances = $this->getFilteredAttendanceQuery($request)
            ->orderBy('attendance_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('leader.reports.print', compact('attendances', 'leader'));
    }

    private function getFilteredAttendanceQuery(Request $request)
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;

        return Attendance::with(['user.division', 'user.employee', 'rejectedBy'])
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->when($request->employee_id, function ($query) use ($request) {
                $query->whereHas('user.employee', function ($employeeQuery) use ($request) {
                    $employeeQuery->where('id', $request->employee_id);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('attendance_date', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('attendance_date', '<=', $request->end_date);
            })
            ->when($request->status, function ($query) use ($request) {
                if ($request->status === 'ditolak') {
                    $query->where('verification_status', 'ditolak');
                } elseif ($request->status === 'belum_checkout') {
                    $query->whereNotNull('check_in_time')
                        ->whereNull('check_out_time')
                        ->where('status', '!=', 'terlambat')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($request->status === 'selesai') {
                    $query->where('status', 'selesai')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($request->status === 'terlambat') {
                    $query->where('status', 'terlambat')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($request->status === 'hadir') {
                    $query->where('status', 'hadir')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                }
            });
    }
}