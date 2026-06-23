<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $divisionId = $request->division_id;

        $employees = Employee::with(['user.division'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->whereHas('user', function ($userQuery) use ($divisionId) {
                    $userQuery->where('division_id', $divisionId);
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $divisions = Division::orderBy('division_name')->get();

        return view('admin.employees.index', compact('employees', 'divisions', 'search', 'divisionId'));
    }

    public function create()
    {
        $divisions = Division::orderBy('division_name')->get();

        return view('admin.employees.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'position' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ], [
            'division_id.required' => 'Divisi wajib dipilih.',
            'name.required' => 'Nama karyawan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'division_id' => $request->division_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'position' => $request->position,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user.division']);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load(['user.division']);
        $divisions = Division::orderBy('division_name')->get();

        return view('admin.employees.edit', compact('employee', 'divisions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($employee->user_id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'position' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $employee) {
            $userData = [
                'division_id' => $request->division_id,
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $employee->user->update($userData);

            $employee->update([
                'position' => $request->position,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            if ($employee->user) {
                $employee->user->delete();
            } else {
                $employee->delete();
            }
        });

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}