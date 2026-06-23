<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\LeaderProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LeaderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $divisionId = $request->division_id;

        $leaders = User::with(['division', 'leaderProfile'])
            ->where('role', 'leader')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $divisions = Division::orderBy('division_name')->get();

        return view('admin.leaders.index', compact('leaders', 'divisions'));
    }

    public function create()
    {
        $divisions = Division::orderBy('division_name')->get();

        return view('admin.leaders.create', compact('divisions'));
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
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'division_id' => $request->division_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'leader',
            ]);

            LeaderProfile::create([
                'user_id' => $user->id,
                'position' => $request->position ?? 'Ketua Ruangan',
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Data ketua ruangan berhasil ditambahkan.');
    }

    public function show(User $leader)
    {
        abort_if($leader->role !== 'leader', 404);

        $leader->load(['division', 'leaderProfile']);

        return view('admin.leaders.show', compact('leader'));
    }

    public function edit(User $leader)
    {
        abort_if($leader->role !== 'leader', 404);

        $leader->load(['division', 'leaderProfile']);
        $divisions = Division::orderBy('division_name')->get();

        return view('admin.leaders.edit', compact('leader', 'divisions'));
    }

    public function update(Request $request, User $leader)
    {
        abort_if($leader->role !== 'leader', 404);

        $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($leader->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'position' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $leader) {
            $userData = [
                'division_id' => $request->division_id,
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'leader',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $leader->update($userData);

            LeaderProfile::updateOrCreate(
                ['user_id' => $leader->id],
                [
                    'position' => $request->position ?? 'Ketua Ruangan',
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );
        });

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Data ketua ruangan berhasil diperbarui.');
    }

    public function destroy(User $leader)
    {
        abort_if($leader->role !== 'leader', 404);

        $leader->delete();

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Data ketua ruangan berhasil dihapus.');
    }
}