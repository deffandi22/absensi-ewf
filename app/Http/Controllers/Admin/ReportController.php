<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Division;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $attendances = $this->getFilteredAttendanceQuery($request)
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $divisions = Division::orderBy('division_name', 'asc')->get();

        return view('admin.reports.index', compact('attendances', 'divisions'));
    }

    public function print(Request $request)
    {
        $attendances = $this->getFilteredAttendanceQuery($request)
            ->orderBy('attendance_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $divisions = Division::orderBy('division_name', 'asc')->get();

        return view('admin.reports.print', compact('attendances', 'divisions'));
    }

    private function getFilteredAttendanceQuery(Request $request)
    {
        return Attendance::with(['user.division', 'user.employee', 'rejectedBy'])
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
            ->when($request->division_id, function ($query) use ($request) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('division_id', $request->division_id);
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