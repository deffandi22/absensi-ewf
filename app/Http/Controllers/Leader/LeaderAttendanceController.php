<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaderAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;

        $attendances = Attendance::with(['user.employee', 'user.division', 'rejectedBy'])
            ->whereHas('user', function ($query) use ($divisionId) {
                $query->where('role', 'employee')
                    ->where('division_id', $divisionId);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('user', function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
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
            })
            ->orderBy('attendance_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('leader.attendances.index', compact('attendances', 'leader'));
    }

    public function show(Attendance $attendance)
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;

        $attendance->load(['user.employee', 'user.division', 'rejectedBy']);

        if (!$attendance->user || $attendance->user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data absensi ini.');
        }

        return view('leader.attendances.show', compact('attendance', 'leader'));
    }

    public function reject(Request $request, Attendance $attendance)
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;

        $attendance->load('user');

        if (!$attendance->user || $attendance->user->division_id !== $divisionId) {
            abort(403, 'Anda tidak memiliki akses untuk menolak data absensi ini.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $attendance->update([
            'verification_status' => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => $leader->id,
            'rejected_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect('/leader/attendances/' . $attendance->id)
            ->with('success', 'Absensi berhasil ditolak.');
    }
}