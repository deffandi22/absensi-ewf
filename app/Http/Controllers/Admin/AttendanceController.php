<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Division;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $divisionId = $request->division_id;
        $date = $request->date;
        $status = $request->status;

        $attendances = Attendance::with(['user.division', 'user.employee', 'rejectedBy'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->whereHas('user', function ($userQuery) use ($divisionId) {
                    $userQuery->where('division_id', $divisionId);
                });
            })
            ->when($date, function ($query) use ($date) {
                $query->whereDate('attendance_date', $date);
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'ditolak') {
                    $query->where('verification_status', 'ditolak');
                } elseif ($status === 'belum_checkout') {
                    $query->whereNotNull('check_in_time')
                        ->whereNull('check_out_time')
                        ->where('status', '!=', 'terlambat')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($status === 'selesai') {
                    $query->where('status', 'selesai')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($status === 'terlambat') {
                    $query->where('status', 'terlambat')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                } elseif ($status === 'hadir') {
                    $query->where('status', 'hadir')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('verification_status')
                                ->orWhere('verification_status', '!=', 'ditolak');
                        });
                }
            })
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $divisions = Division::orderBy('division_name', 'asc')->get();

        return view('admin.attendances.index', compact(
            'attendances',
            'divisions',
            'search',
            'divisionId',
            'date',
            'status'
        ));
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['user.division', 'user.employee', 'rejectedBy']);

        return view('admin.attendances.show', compact('attendance'));
    }
}