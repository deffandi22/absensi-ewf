<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $leader = auth()->user();
        $divisionId = $leader->division_id;

        $employees = User::with(['employee', 'division'])
            ->where('role', 'employee')
            ->where('division_id', $divisionId)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('leader.employees.index', compact('employees', 'leader'));
    }

    public function show(User $employee)
    {
        $leader = auth()->user();

        if ($employee->role !== 'employee' || $employee->division_id !== $leader->division_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail karyawan ini.');
        }

        $employee->load(['employee', 'division']);

        return view('leader.employees.show', [
            'leader' => $leader,
            'employeeUser' => $employee,
        ]);
    }
}
