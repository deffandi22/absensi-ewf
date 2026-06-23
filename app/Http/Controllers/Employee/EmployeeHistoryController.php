<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class EmployeeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $attendances = Attendance::with('rejectedBy')
            ->where('user_id', $user->id)
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
            })
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('employee.history.index', compact('attendances'));
    }

    public function show(Attendance $attendance)
    {
        $user = auth()->user();

        if ($attendance->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data absensi ini.');
        }

        $attendance->load(['user.employee', 'user.division', 'rejectedBy']);

        return view('employee.history.show', compact('attendance'));
    }
}